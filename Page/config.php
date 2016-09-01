<?php if (!defined('PLX_ROOT')) exit; 
# Control du token du formulaire
plxToken::validateFormToken($_POST);

if(!empty($_POST)) {
	if (!empty($_POST['Titre']) AND !empty($_POST['mnuPos']) AND !empty($_POST['template']) AND !empty($_POST['url']))  {

		#Supprimer les accents de l'URL et passe en minuscules
		$url_page = plxUtils::title2url($_POST['url']);
		
		$plxPlugin->setParam('mnuDisplay', $_POST['mnuDisplay'], 'numeric');
		$plxPlugin->setParam('Titre', $_POST['Titre'], 'cdata');
		$plxPlugin->setParam('url', $url_page, 'cdata');
		$plxPlugin->setParam('mnuPos', $_POST['mnuPos'], 'numeric');
		$plxPlugin->setParam('template', $_POST['template'], 'string');
		$plxPlugin->saveParams();
	}

	if (!empty($_POST['texte'])) {
		$plxPlugin->setParam('texte', $_POST['texte'], 'string');
		$plxPlugin->saveParams();
	}	
}
	
	# initialisation des variables 
	$var['mnuDisplay'] =  $plxPlugin->getParam('mnuDisplay')=='' ? 1 : $plxPlugin->getParam('mnuDisplay');
	$var['Titre'] =  $plxPlugin->getParam('Titre')=='' ? 'page' : $plxPlugin->getParam('Titre');
	$var['url'] = $plxPlugin->getParam('url')=='' ? 'page' : $plxPlugin->getParam('url');
	$mnuPos['mnuPos'] =  $plxPlugin->getParam('mnuPos')=='' ? 2 : $plxPlugin->getParam('mnuPos');
	$template['template'] = $plxPlugin->getParam('template')=='' ? 'static.php' : $plxPlugin->getParam('template');
	
	# On récupère les templates des pages statiques
	$files = plxGlob::getInstance(PLX_ROOT.'themes/'.$plxAdmin->aConf['style']);
	if ($array = $files->query('/^static(-[a-z0-9-_]+)?.php$/')) {
		foreach($array as $k=>$v)
			$aTemplates[$v] = $v;
	}
?>	


<p>
	<h2><?php echo $plxPlugin->getInfo("description") ?></h2>
</p>

<!-- navigation sur la page configuration du plugin -->
	<nav id="tabby-1" class="tabby-tabs" data-for="example-tab-content">
		<ul>
			<li><a data-target="tab1" class="active" href="#"><?php $plxPlugin->lang('L_NAV_LIEN1') ?></a></li>
			<li><a data-target="tab2" href="#"><?php $plxPlugin->lang('L_NAV_LIEN2') ?></a></li>
			<li><a data-target="tab3" href="#"><?php $plxPlugin->lang('L_NAV_LIEN3') ?></a></li>
		</ul>
	</nav> 

    <!-- contenu de la page 1 - configuration -->
	<div class="tabby-content" id="example-tab-content">

	<div data-tab="tab1">	

		<div class="formulaire_stripe">

			<form action="parametres_plugin.php?p=Page" method="post">

				<?php $mnuDisplay = $plxPlugin->getParam("mnuDisplay"); ?>

				<fieldset>
					<div class="grid">
						<div class="col sml-12">
							<label for="mnuDisplay">Afficher la page dans la navigation</label>
							<select name="mnuDisplay" id="mnuDisplay">
								<option value="1"  <?php if ($mnuDisplay == '1') { echo'selected';}?> >Oui</option>
								<option value="0" <?php if ($mnuDisplay == '0') { echo'selected';}?> >Non</option>
							</select>
						</div>	
					</div>

					<div class="grid">
						<div class="col sml-12">
							<label for="Titre">Titre de la page</label>
							<input id="Titre" name="Titre"  maxlength="255" value="<?php echo $plxPlugin->getParam("Titre"); ?>">
						</div>
					</div>

					<div class="grid">
						<div class="col sml-12">
							<label for="url">URL de la page</label>
							<input id="url" name="url"  maxlength="255" value="<?php echo $plxPlugin->getParam("url"); ?>">
						</div>
					</div>
					
					<div class="grid">
						<div class="col sml-12">	
							<label for="mnuPos">Position de la page</label>
							<input id="mnuPos" name="mnuPos"  maxlength="255" value="<?php echo $plxPlugin->getParam("mnuPos"); ?>">
						</div>
					</div>

					<div class="grid">
						<div class="col sml-12">
							<label for="template">Template de votre page</label>
							<?php plxUtils::printSelect('template', $aTemplates, $template) ?>
						</div>
					</div>	

				</fieldset>
					
				<p class="in-action-bar">
					<?php echo plxToken::getTokenPostMethod() ?>
					<input type="submit" name="submit" value="<?php $plxPlugin->lang('SUBMIT') ?>" />
				</p>

			</form>
		</div>
	</div>

	<!-- contenu de la page 2  -->
	<div data-tab="tab2">
		<form action="parametres_plugin.php?p=Page" method="post">
			<fieldset>
				<label for="id_content">texte en haut de page</label>
				<textarea id="id_content" rows="5" name="texte"><?php echo $plxPlugin->getParam('texte'); ?></textarea>
			</fieldset>

			<p class="in-action-bar">
				<?php echo plxToken::getTokenPostMethod() ?>
				<input type="submit" name="submit" value="<?php $plxPlugin->lang('SUBMIT') ?>" />
			</p>
		</form>
	</div>


	<!-- contenu de la page 3  -->
	<div data-tab="tab3">

	</div>



	</div>

	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="<?php echo PLX_PLUGINS ?>Page/js/jquery.tabby.js"></script>
	<script>
	    $(document).ready(function(){
	        $('#tabby-1').tabby();
	    });
	</script>	
	