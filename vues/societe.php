<?php
if (isset($add2Cart['message'])) {
    echo $add2Cart['message'];
}
?>
<h4 class="underline">Catalogue de la <?php echo $nomSociete['nomSociete']; ?></h4>
<div id="idSociete" style="display: none;"><?php echo $_GET['act']; ?></div>
<div class="left">
    <?php
    $menu = afficher_menu(0, 0, $donnees);
    if (isset($menu)) {
        echo $menu;
    }
    ?>
    <div style="clear: both;"></div>
    <div style="width: 248px; border: 1px solid black; text-align: center;">
        Recherche rapide<br/>
        <form method="post" action="societe.html" id="rapidSearchForm">
            <input type="hidden" name="searchProduct" value="1"/>
            <div class="left" style="width: 124px;">
                <label for="refSearch">Ref :</label><br/>
                <input type="text" style="width: 80px;" id="refSearch" name="refSearch"/>
            </div>
            <div class="left" style="width: 124px;">
                <label for="spinnerSearchQte">Quantité :</label><br/>
                <input type="text" name="qteSearch" id="spinnerSearchQte" style="font-size: 13px; width: 57px;" placeholder="min"/>
                <script>
                    $(function() {
                        $('#spinnerSearchQte').spinner({
                            min: 0,
                            max: 999,
                            step: 1
                        });
                    });
                </script>
            </div>
            <div style="clear: both;"></div>
            <input type="submit" class="classButton" value="Ajouter au panier" />
        </form>
    </div>
</div>

<div id="result" style="width: 1000px; margin: auto;"></div>
<script type="text/javascript">
    var ROOTPATH = "/prog2/";
    var idS = $('#idSociete').text();

    // ***************** GESTION DU MENU **************************************

    $('.menuCategorie').click(function(event) {
        event.preventDefault();
        //event.stopPropagation();
        postForm($(this));
        derouleMenu($(this));
    });
    function hideFatherAndSons(element) {
        console.log("ok");
        $(element).next().slideUp();
        if ($(element).hasClass('menuCategorie')) {
            var sons = $(element).next().children('li').children('form.menuCategorie'); // rappeler deroule menu sur chacun d'entre eux
            for (var i = 0; i < sons.length; ++i)
                hideFatherAndSons($(sons[i]));
        }
    }

    function derouleMenu(element, event) {
        console.log("menu");

        // Si on est pas dans la même catégorie, on cache les autres
        var categories = $(element).parent().siblings().children('form.menuCategorie'); // On sélectionne les autres catégories
        for (var i = 0; i < categories.length; ++i)
            if ($(categories[i]) != $(element))
                hideFatherAndSons($(categories[i]));

        // Si l'élément père doit être caché, on cache aussi tous les fils
        if ($(element).next().css('display') != 'none')
            hideFatherAndSons($(element));
        // Sinon on se contente juste de dérouler le premier fils.
        else
            $(element).next().slideDown();
    }

    function postForm(element) {
        function printDataReceived(data) {
            // Traitement et affichage des données reçues sous forme de Json : 
            var newContent = '<div id="right"><ul class="right_content">';
            if (data) {
                for (var i = 0; i < data.length; ++i) {
                    //newContent += ''+data[i].prixProduit+'<br/>';
                    //newContent += ''+data[i].quantiteProduit+'<br/>';
                    newContent += '<li style="list-style: none; width: 220px; margin: auto;float: left;">';
                    newContent += '<div id="id555288" class="img_index">';
                    newContent += '<a href="image?id=555288" title="">';
                    newContent += '<img alt="imgProduit" style="width: 128px; height: 128px;" src="img/' + data[i].imgProduit + '" />';
                    newContent += '</a><br/>';
                    newContent += '<span class="prixProduit">Prix: ' + data[i].prixProduit + '€</span><br/>';
                    newContent += '<span class="prixProduit">Ref: ' + data[i].refProduit + '</span><br/>';
                    newContent += '<form method="POST" class="formAdd2Cart" action="societe-' + idS + '.html">';
                    newContent += 'Quantité: <select name="quantiteProduit">';
                    var o = 1;
                    do {
                        j = o * data[i].minQte;
                        newContent += '<option value="' + j + '">' + j + '</option>';
                        o++;
                    } while (j < data[i].quantiteProduit);
                    newContent += '</select>';
                    newContent += '<input type="hidden" name="idProduit" value="' + data[i].idProduit + '"><input type="hidden" name="idSociete" value="' + data[i].idSociete + '"><br/>';
                    newContent += '<input type="submit" class="submit2Cart" name="add2Cart" value="Ajouter au panier" style="cursor: pointer;color: #fff;border: 1px solid grey;background-color: #2db3e6;height: 22px;">';
                    newContent += '</form>';
                }
                newContent += '</ul></div>';
            }
            else {
                newContent += '<center><span>Aucun Résultat</span></center><br/>';
                newContent += '</ul></div>';
            }
            $('#result').fadeOut();
            var section = $(document.createElement("section")).css('display', 'none').html(newContent);
            $('#result').html(section).show(50, function() {
                section.fadeIn()
            });
            $('#result').fadeIn();
        }
        $.post(ROOTPATH + 'societe.html', {idCategorie: $(element).children().val(), idSociete: $('#idSociete').text()}, function(data) {
            printDataReceived(data);
        }, 'json');
        /*$.ajax({type:"POST", data: {idCategorie: $(this).children().val() , idSociete: $('#idSociete').text()}, url:""+ROOTPATH+"societe.html",success: function(data){
         printDataReceived(data)	
         });
         }*/
    }

    $("#rapidSearchForm").submit(function(event) {
        event.preventDefault();
        $.ajax({url: "societe.html", type: "POST",
            data: $(this).serialize()
                    //On envoie le formulaire qui contient: idProduit et qteProduit
        });
    });

</script>
