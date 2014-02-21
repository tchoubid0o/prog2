<h4>Catalogue de <?php echo $nomSociete['nomSociete']; ?></h4>
<div id="idSociete" style="display: none;"><?php echo $_GET['act']; ?></div>
<?php 
	echo afficher_menu(0, 0, $donnees);
?>

<p class="article">
    <img alt="imgProduit" src="#" />
    <span class="libelleProduit">nomProd</span> <span class="prixProduit">75 €</span>
    qte : <input name="quantiteProduit"/>
    
    <input type="hidden" name="idProduit" value="4">
    <input type="hidden" name="idSociete" value="8">
    <input type="hidden" name="idCategorie" value="10">
    <a class="add_article" href="#" title="ajouter article">Ajouter Article</a>
    <a class="remove_article" href="#" title="ajouter article">Supprimer Article</a>
    <a class="empty_panier" href="#" title="ajouter article">Vider le panier</a>

    
</p>

<section class="main_content">
    <table>
        <th>
             <td>Produit</td>
             <td>Quantité</td>
             <td>Prix</td>
        </th>
    <tr>
        <td></td>
        <td></td>
        <td></td>    
    </tr>
    </table>
</section>

<div id="result" style="width: 1000px; margin: auto;"></div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
    var ROOTPATH ="/virolle2/";
                
    $('.empty_panier').click(function(){localStorage.setItem("panier", null);});
   
    $('.remove_article').click(function(){
        // Regarder si l'article est présent, si non : on ne fait rien
        // Si oui : on le supprimer, puis on regarde si il reste d'autres articles pour cette societe. (si non : on supprime l'id en question)'
    });
     
    $('.add_article').click(function(event){
        
        function addToPannier (article){ 
            if (isNaN(article.quantiteProduit) || article.quantiteProduit == ""){
                return ;
            }
            
            console.log(article.idProduit);
            var panier = JSON.parse(localStorage.getItem("panier"));
            // Si il n'y a pas encore de pannier stocké en mémoire on en créé un.
            if (panier == null){
                panier = new Object();
                panier.societe = new Array();
            }
            
            // Parcours du panier pour savoir si il y a déjà des articles de la même société dans le panier
            var idSocieteInArray = -1;
            for(var i =0; i<panier.societe.length && idSocieteInArray == -1; ++i){
                if(panier.societe[i].idSociete == article.idSociete)
                    idSocieteInArray = i;
            }
            
            // Si il n'y en a pas, on créé un panier pour cette societe
            if(idSocieteInArray == -1){
                console.log("creation array"+article.idSociete);
                idSocieteInArray = panier.societe.length;
                panier.societe[idSocieteInArray] = new Object();
                panier.societe[idSocieteInArray].idSociete = article.idSociete;
                panier.societe[idSocieteInArray].articles= new Array(); // TODO à déplacer ailleurs.
            }
          
            // Parcours du pannier pour savoir si l'article existe déjà dans le pannier ou pas.
            var idArticleInArray = -1;
            for(var i =0; i<panier.societe[idSocieteInArray].articles.length && idArticleInArray == -1; ++i){
                if(panier.societe[idSocieteInArray].articles[i].idProduit == article.idProduit)
                    idArticleInArray = i;
            }
            
            // Si l'article est déjà dans la liste, on ajoute la quantité à la précédente (on considère que le prix ne peut pas changer)
            // Sinon on ajoute l'article
            if(idArticleInArray != -1)
                panier.societe[idSocieteInArray].articles[idArticleInArray].quantiteProduit =
                parseInt(panier.societe[idSocieteInArray].articles[idArticleInArray].quantiteProduit) + parseInt(article.quantiteProduit);
            else
                panier.societe[idSocieteInArray].articles[panier.societe[idSocieteInArray].articles.length] = article
            
            // Mise à jour des données en mémoire.
            localStorage.setItem("panier", JSON.stringify(panier));  
            console.log(localStorage.getItem("panier"));
        }
        
        // Création de l'article en javascript
        var thisArticle = $(this).parent();
        var article = {  
            idProduit : $(thisArticle).children('[name="idProduit"]').val(),
            idSociete : $(thisArticle).children('[name="idSociete"]').val(),
            idCategorie : $(thisArticle).children('[name="idCategorie"]').val(),
            libelleProduit : $(thisArticle).children('.libelleProduit').html(),
            quantiteProduit : $(thisArticle).children('[name="quantiteProduit"]').val(),
            prixProduit : parseFloat($(thisArticle).children('.prixProduit').html())
        };
        
        addToPannier(article);
    });
    
    // ***************** GESTION DU MENU **************************************
    
		$('.menuCategorie').click(function(event){
            event.preventDefault();
			//event.stopPropagation();
			postForm($(this));
			derouleMenu($(this));	
		});
		function hideFatherAndSons(element){
			console.log("ok");
			$(element).next().slideUp();
			if ($(element).hasClass('menuCategorie')){
				var sons = $(element).next().children('li').children('form.menuCategorie'); // rappeler deroule menu sur chacun d'entre eux
				for(var i= 0; i<sons.length; ++i)
					hideFatherAndSons($(sons[i]));
			}
		}
	
		function derouleMenu(element, event){
			
			
			// Si on est pas dans la même catégorie, on cache les autres
			var categories = $(element).parent().siblings().children('form.menuCategorie'); // On sélectionne les autres catégories
			for(var i= 0; i<categories.length; ++i)
				if($(categories[i]) != $(element))
					hideFatherAndSons($(categories[i]));
					
		    // Si l'élément père doit être caché, on cache aussi tous les fils
			if ($(element).next().css('display') != 'none')
				hideFatherAndSons($(element));
			// Sinon on se contente juste de dérouler le premier fils.
			else 
				$(element).next().slideDown();
		};
		function postForm(element){
			function printDataReceived(data){
			// Traitement et affichage des données reçues sous forme de Json : 
				var newContent = '<div id="allAds">';
				if(data){
					for (var i=0;i<data.length;++i){
						//newContent += ''+data[i].prixProduit+'<br/>';
						//newContent += ''+data[i].quantiteProduit+'<br/>';
						newContent += '<div class="itemList">';
						newContent += '<img alt="imgProduit" src="#" />';
						newContent += '<span class="libelleProduit">'+data[i].libelleProduit+'</span>';
						newContent += '<span class="prixProduit">'+data[i].prixProduit+'€</span>';
						newContent += '<select name="quantiteProduit">';
						var o = 1;
						do{
							j = o * data[i].minQte; 
							newContent += '<option value="'+j+'">'+j+'</option>';
							o++;
						}while(j<data[i].quantiteProduit);
						newContent += '</select>';
						newContent += '<input type="hidden" name="idProduit" value="'+data[i].idProduit+'"><input type="hidden" name="idSociete" value="'+data[i].idSociete+'"><input type="hidden" name="idCategorie" value="'+data[i].idCategorie+'">';
						newContent += '<a class="add_article" href="#" title="ajouter article">Ajouter Article</a>';
						newContent += '<a class="remove_article" href="#" title="ajouter article">Supprimer Article</a>';
						newContent += '<a class="empty_panier" href="#" title="ajouter article">Vider le panier</a>';
						newContent += '</div>';
					}	
					newContent += '</div><div style="clear:both;"></div>';
				}
				else{
					newContent += '<center><span>Aucun Résultat</span></center><br/>';
					newContent += '</div><div style="clear:both;"></div>';
				}
				$('#result').fadeOut();
				var section = $(document.createElement("section")).css('display','none').html(newContent);
				$('#result').html(section).show(50,function(){section.fadeIn()});
				$('#result').fadeIn();
			}
			$.post(ROOTPATH+'societe.html', {idCategorie: $(element).children().val() , idSociete: $('#idSociete').text()}, function(data){
			printDataReceived(data);
			}, 'json');
			/*$.ajax({type:"POST", data: {idCategorie: $(this).children().val() , idSociete: $('#idSociete').text()}, url:""+ROOTPATH+"societe.html",success: function(data){
					printDataReceived(data)	
					});
			}*/
		}
		
</script>
