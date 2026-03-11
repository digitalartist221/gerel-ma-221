<?php
namespace Packages\View;

class MadelineView {
    private static $blocks = [];
    private static $componentProps = [];
    private static $layout = null;

    public static function render($view, $data = []) {
        $viewFile = __DIR__ . '/../../App/Views/' . $view . '.madeline.php';
        if (!file_exists($viewFile)) {
            $viewFile = __DIR__ . '/../../App/Views/' . $view . '.php';
            if (!file_exists($viewFile)) {
                throw new \Exception("Vue non trouvée : " . $view);
            }
        }

        // Sauvegarder l'état actuel pour la récursion
        $previousLayout = self::$layout;
        self::$layout = null;

        // Extraction des données variables pour la vue
        extract($data);
        
        $cacheDir = __DIR__ . '/../../storage/cache/views';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        $compiledFile = $cacheDir . '/' . md5($viewFile) . '.php';
        
        $env = class_exists('\Core\Config') ? \Core\Config::get('env', 'production') : 'production';
        $lifetime = class_exists('\Core\Config') ? \Core\Config::get('view_cache_lifetime', 3600) : 3600;
        
        $needsRebuild = false;
        
        if ($env === 'local') {
            $needsRebuild = true;
        } elseif (!file_exists($compiledFile)) {
            $needsRebuild = true;
        } elseif (filemtime($viewFile) > filemtime($compiledFile)) {
            $needsRebuild = true;
        } elseif ($lifetime > 0 && (time() - filemtime($compiledFile)) > $lifetime) {
            $needsRebuild = true;
        }
        
        if ($needsRebuild) {
            $content = file_get_contents($viewFile);
            $compiledContent = self::compile($content);
            file_put_contents($compiledFile, $compiledContent);
        }

        ob_start();
        require $compiledFile;
        $output = ob_get_clean();
        
        // Si la vue a défini un parent via @indi
        if (self::$layout) {
            $layoutToRender = self::$layout;
            self::$layout = null; // Eviter boucle infinie
            
            $output = self::render($layoutToRender, $data);
        }

        // MODE TURBO : Si c'est une requête Madeline.js, on renvoie du JSON direct
        // Note: On le fait uniquement à la fin du rendu racine (quand previousLayout est null)
        // ou si aucun layout n'est utilisé, pour s'assurer d'avoir toutes les sections.
        if ($previousLayout === null && isset($_SERVER['HTTP_X_MADELINE_REQUEST'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'madeline' => true,
                'title' => self::$componentProps['title'] ?? self::$componentProps['pageTitle'] ?? 'Madeline App',
                'head' => (self::$componentProps['head'] ?? '') . (self::$componentProps['extra_head'] ?? ''),
                'html' => self::$componentProps['content'] ?? $output
            ]);
            exit;
        }

        // Nettoyage des sections après le rendu final pour éviter les fuites de sections
        if ($previousLayout === null) {
            self::$componentProps = [];
        }

        // Restaurer le layout précédent pour remonter la pile d'héritage
        self::$layout = $previousLayout;
        
        return $output;
    }

    public static function setLayout($layout) {
        self::$layout = $layout;
    }

    private static function compile($content) {
        // ================================================================
        // ÉTAPE 0 (ABSOLUMENT EN PREMIER) : Supprimer les commentaires
        // Wolof {{-- ... --}} avant TOUT le reste.
        // Si on ne le fait pas en premier, {{ }} les compile comme des
        // variables PHP et provoque un ParseError.
        // ================================================================
        $content = preg_replace('/\{\{--.*?--\}\}/s', '', $content);

        // ================================================================
        // PROTECTION : Extraire les blocs <pre> ET <code> inline
        // avant toute compilation pour qu'aucune directive ne soit
        // transformée à l'intérieur des exemples de code.
        // ================================================================
        $preBlocks    = [];
        $preCounter   = 0;
        
        // 1. D'abord les <pre>...</pre> (priorité max, peuvent contenir des <code>)
        $content = preg_replace_callback('/<pre(?:[^>]*)>.*?<\/pre>/si', function ($matches) use (&$preBlocks, &$preCounter) {
            $placeholder = '%%MADELINE_PRE_BLOCK_' . $preCounter . '%%';
            $preBlocks[$preCounter] = $matches[0];
            $preCounter++;
            return $placeholder;
        }, $content);
        
        // 2. Ensuite les <code>...</code> inline restants
        $content = preg_replace_callback('/<code(?:[^>]*)>.*?<\/code>/si', function ($matches) use (&$preBlocks, &$preCounter) {
            $placeholder = '%%MADELINE_PRE_BLOCK_' . $preCounter . '%%';
            $preBlocks[$preCounter] = $matches[0];
            $preCounter++;
            return $placeholder;
        }, $content);

        // Directives Wolof
        
        // @indi('layout') -> Include/Extend (Deferred)
        $content = preg_replace('/@indi\([\'"](.+?)[\'"]\)/', '<?php \Packages\View\MadelineView::setLayout(\'$1\'); ?>', $content);
        
        // @baat($items as $item) -> foreach
        $content = preg_replace('/@baat\(((?:[^)(]+|\([^)(]*\))*)\)/', '<?php foreach($1): ?>', $content);
        $content = str_replace('@jeexbaat', '<?php endforeach; ?>', $content);

        // @ndax($condition) -> if
        $content = preg_replace('/@ndax\(((?:[^)(]+|\([^)(]*\))*)\)/', '<?php if($1): ?>', $content);
        $content = preg_replace('/@ndaxam\(((?:[^)(]+|\([^)(]*\))*)\)/', '<?php elseif($1): ?>', $content);
        $content = str_replace('@xaaj', '<?php else: ?>', $content);
        $content = str_replace('@jeexndax', '<?php endif; ?>', $content);

        // @def('content') -> start section
        $content = preg_replace('/@def\([\'"](.+?)[\'"]\)/', '<?php \Packages\View\MadelineView::startSection(\'$1\'); ?>', $content);
        $content = str_replace('@jeexdef', '<?php \Packages\View\MadelineView::endSection(); ?>', $content);

        // Directives d'Authentification : @miingi fi (ils sont là/présents)
        $content = str_replace('@miingi fi', '<?php if(isset($_SESSION[\'user\'])): ?>', $content);
        $content = str_replace('@jeexmiingi', '<?php endif; ?>', $content);

        // @biir('content') -> yield section content
        $content = preg_replace('/@biir\([\'"](.+?)[\'"]\)/', '<?php echo \Packages\View\MadelineView::yieldContent(\'$1\'); ?>', $content);

        // Gestion des Composants: <x-alert type="danger">Slot content</x-alert>
        $content = self::compileComponents($content);

        // Directive de sécurité @csrf
        $content = str_replace('@csrf', '<?php echo \'<input type="hidden" name="csrf_token" value="\'.\App\Middlewares\CsrfMiddleware::getToken().\'">\'  ; ?>', $content);

        // Echo {{ $var }} avec protection XSS
        // Le (?!--) empêche de compiler {{-- --}} si jamais un commentaire
        // avait échappé à la suppression ci-dessus (double protection)
        $content = preg_replace('/\{\{(?!--)\s*(.+?)\s*\}\}/', '<?php echo htmlspecialchars((string)($1 ?? \'\'), ENT_QUOTES); ?>', $content);
        
        // Echo non-échappé {!! $var !!}
        $content = preg_replace('/\{!!\s*(.+?)\s*!!\}/', '<?php echo $1 ?? \'\'; ?>', $content);

        // ================================================================
        // RESTAURATION : Réinjecter les blocs <pre> protégés
        // ================================================================
        foreach ($preBlocks as $i => $block) {
            $content = str_replace('%%MADELINE_PRE_BLOCK_' . $i . '%%', $block, $content);
        }

        return $content;
    }

    private static function compileComponents($content) {
        // Balises avec contenu : <x-component-name attr="value">Content</x-component-name>
        $content = preg_replace_callback(
            '/<x-([a-zA-Z0-9_-]+)(.*?)>(.*?)<\/x-\1>/s',
            function ($matches) {
                $component = $matches[1];
                $attributesString = $matches[2];
                $slot = $matches[3];
                
                $attributes = self::parseAttributes($attributesString);
                $attributes['slot'] = addslashes(trim($slot));
                
                $attrArrayString = self::buildArrayString($attributes);
                return "<?php echo \Packages\View\Component::renderComponent('$component', $attrArrayString); ?>";
            },
            $content
        );

        // Balises auto-fermantes : <x-component-name attr="value" />
        $content = preg_replace_callback(
            '/<x-([a-zA-Z0-9_-]+)(.*?)\/>/s',
            function ($matches) {
                $component = $matches[1];
                $attributesString = $matches[2];
                
                $attributes = self::parseAttributes($attributesString);
                $attrArrayString = self::buildArrayString($attributes);
                
                return "<?php echo \Packages\View\Component::renderComponent('$component', $attrArrayString); ?>";
            },
            $content
        );

        return $content;
    }

    private static function parseAttributes($attributesString) {
        $attributes = [];
        preg_match_all('/([a-zA-Z0-9_-]+)=[\'"](.*?)[\'"]/', $attributesString, $attrMatches, PREG_SET_ORDER);
        foreach ($attrMatches as $match) {
            $attributes[$match[1]] = addslashes($match[2]);
        }
        return $attributes;
    }

    private static function buildArrayString($attributes) {
        $str = "[";
        $first = true;
        foreach ($attributes as $key => $val) {
            if (!$first) $str .= ", ";
            $str .= "'$key' => '$val'";
            $first = false;
        }
        $str .= "]";
        return $str;
    }

    public static function startSection($name) {
        self::$blocks[] = $name;
        ob_start();
    }

    public static function endSection() {
        $name = array_pop(self::$blocks);
        if (!isset(self::$componentProps[$name])) {
            self::$componentProps[$name] = '';
        }
        self::$componentProps[$name] .= ob_get_clean();
    }

    public static function yieldContent($name) {
        return self::$componentProps[$name] ?? '';
    }
}
