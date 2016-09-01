<?php
class Page extends plxPlugin {
 
    public function __construct($default_lang){
    # Appel du constructeur de la classe plxPlugin (obligatoire)
    parent::__construct($default_lang);
    
    
    # Pour accéder à une page d'administration
    $this->setAdminProfil(PROFIL_ADMIN,PROFIL_MANAGER);
    # Personnalisation du menu admin
    $this->setAdminMenu('Framework Page', 1, 'Légende du lien');
    
    # Pour accéder à une page de configuration
    $this->setConfigProfil(PROFIL_ADMIN,PROFIL_MANAGER);
    # Déclaration des hooks
    $this->addHook('ThemeEndHead', 'ThemeEndHead');
    $this->addHook('plxShowConstruct', 'plxShowConstruct');
    $this->addHook('plxMotorPreChauffageBegin', 'plxMotorPreChauffageBegin');
    $this->addHook('plxShowStaticListEnd', 'plxShowStaticListEnd');
    $this->addHook('plxShowPageTitle', 'plxShowPageTitle');
    $this->addHook('SitemapStatics', 'SitemapStatics');
    $this->addHook('AdminTopEndHead', 'AdminTopEndHead');
    } 


    public function AdminTopEndHead() { ?>
    
        <link rel="stylesheet" href="<?php echo PLX_PLUGINS ?>Page/css/admin_style.css">

    <?php
    }
    
    public function ThemeEndHead() { ?>
    
        <link rel="stylesheet" href="<?php echo PLX_PLUGINS ?>Page/css/style.min.css">
        
     <?php 
    }
   
    public function plxShowConstruct() {
        # infos sur la page statique
        $string  = "if(\$this->plxMotor->mode=='".$this->getParam('url')."') {";
        $string .= "    \$array = array();";
        $string .= "    \$array[\$this->plxMotor->cible] = array(
            'name'      => '".addslashes($this->getParam('Titre'))."',
            'menu'      => '',
            'url'       => 'page',
            'readable'  => 1,
            'active'    => 1,
            'group'     => ''
        );";
        $string .= "    \$this->plxMotor->aStats = array_merge(\$this->plxMotor->aStats, \$array);";
        $string .= "}";
        echo "<?php ".$string." ?>";
    }

    public function plxMotorPreChauffageBegin() {
        $template = $this->getParam('template')==''?'static.php':$this->getParam('template');
        $string = "
        if(\$this->get && preg_match('/^".$this->getParam('url')."\/?/',\$this->get)) {
            \$this->mode = '".$this->getParam('url')."';
            \$prefix = str_repeat('../', substr_count(trim(PLX_ROOT.\$this->aConf['racine_statiques'], '/'), '/'));
            \$this->cible = \$prefix.'plugins/Page/static';
            \$this->template = '".$template."';
            return true;
        }
        ";
        echo "<?php ".$string." ?>";
    }

    public function plxShowStaticListEnd() {
        # ajout du menu pour accèder à la page de Page
        if($this->getParam('mnuDisplay')) {
            echo "<?php \$class = \$this->plxMotor->mode=='".$this->getParam('url')."'?'active':'noactive'; ?>";
            echo "<?php array_splice(\$menus, ".($this->getParam('mnuPos')-1).", 0, '<li><a class=\"static '.\$class.'\" href=\"'.\$this->plxMotor->urlRewrite('?".$this->getParam('url')."').'\" title=\"".addslashes($this->getParam('Titre'))."\">".addslashes($this->getParam('Titre'))."</a></li>'); ?>";
        }

    }

    #Balise html <title>
    public function plxShowPageTitle() {
    echo '<?php
        if($this->plxMotor->mode == "'.$this->getParam('url').'") {
            echo "'.$this->getParam('Titre').' - ".plxUtils::strCheck($this->plxMotor->aConf["title"]);
            return true;
        }
    ?>';

    }

    #Référence la page de contact dans le sitemap
    public function SitemapStatics() {
        echo '<?php
        echo "\n";
        echo "\t<url>\n";
        echo "\t\t<loc>".$plxMotor->urlRewrite("?'.$this->getParam('url').'")."</loc>\n";
        echo "\t\t<changefreq>monthly</changefreq>\n";
        echo "\t\t<priority>0.8</priority>\n";
        echo "\t</url>\n";
        ?>';
    }
      
} // class Page
?>