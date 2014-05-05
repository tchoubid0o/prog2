<?php
if (isset($_SESSION['id'])) {
    if ($_SESSION['adm'] == 1) {
        if(!isset($_GET['param2'])){$_GET['param2'] = null;}
        if ($_GET['param2'] != "addCat") {
            ?>
            <a href="<?php echo ROOTPATH; ?>/index.html">Index</a>
            <a href="<?php echo ROOTPATH; ?>/admin.html">Administration</a>
            <?php
            if (isset($_GET['page']) && $_GET['page'] == "societeadm") {
                echo "> Société n°" . $_GET['param1'] . "";
            }
            ?>
            <div id="productDetails">
            </div>

            <?php
            if (isset($add2Cart['message'])) {
                echo $add2Cart['message'];
            }
            ?>
            <h4 class="underline">Catalogue de la <?php echo $nomSociete['nomSociete']; ?></h4>
            <div id="idSociete" style="display: none;"><?php
                if (isset($_GET['param1'])) {
                    echo $_GET['param1'];
                }
                ?></div>
            <div id="idPage" style="display: none;"><?php
                if (isset($_GET['idPage'])) {
                    echo $_GET['idPage'];
                } else {
                    echo "1";
                }
                ?></div>
            <div id="idCat" style="display: none;"><?php
                if (isset($_GET['idCat'])) {
                    echo $_GET['idCat'];
                } else {
                    echo "0";
                }
                ?></div>
            <div class="left">
                <?php
                $menu = afficher_menu(0, 0, $donnees);
                if (isset($menu)) {
                    echo $menu;
                }
                ?>
                <div style="clear: both;"></div>
                <div>
                    <form class="nbPerPage" method="get" action="">
                        <label>Affichage par page:</label>
                        <select>
                            <option value="1">1</option>
                            <option value="3">3</option>
                            <option value="6">6</option>
                        </select>
                    </form>
                </div>
            </div>

            <div id="result" style="width: 1000px; margin: auto;"></div><div id="pagination" style="display: none; float: right;margin-right: 68px;">

            </div>
            <script type="text/javascript">
                var ROOTPATH = "/prog2/";
                var idS = $('#idSociete').text();

                // ***************** GESTION DE LA PAGINATION ******************************
                // Si on clique sur une page, on poste le formulaire, on affiche le JSON avec nos produits puis on affiche la pagination
                //CETTE FONCTION DOIT ETRE APPELLER DES QUON GENERE LA PAGINATION
                function generatePagination() {
                    $(".paginationForm").click(function(event) {
                        $('#pagination').hide();
                        //event.stopPropagation();
                        event.preventDefault();
                        $("#idPage").html($(this).attr("data-value"));
                        $.post(ROOTPATH + 'societeadm.html', {idCategorie: $('#idCat').text(), idSociete: $('#idSociete').text(), nbProduct: $('.nbPerPage select').val(), idPage: parseInt($(this).attr("data-value"))}, function(data) {
                            generateData(data);

                            generatePagination();
                            setTimeout(function() {
                                $('#pagination').show();
                            }, 1000);

                            //Formulaire d'ajout un produit au panier, puis refresh du miniPanier
                            $(".formAdd2Cart").submit(function(event) {
                                event.preventDefault();
                                $.ajax({url: "societeadm.html",
                                    type: "POST",
                                    dataType: "json",
                                    data: $(this).serialize() + "&add2Cart=1"
                                }).done(function(data) {
                                    alert(data[0].message);
                                    $("#previewMinicart").html(data.miniCart);
                                });
                            });

                            showProductDetails();
                        }, 'json');
                    });
                }
                ;

                // ***************** Gestion nb Product par page **************************

                //Fonction qui s'execute lorsque l'on change le nombre de produit par page
                $('.nbPerPage select').change(function() {
                    $("#idPage").html("1");
                    $('#pagination').hide();
                    var idCat;
                    if ($("#currentCategory").html().length != 0) {
                        idCat = $("#currentCategory").html();
                    }
                    else {
                        idCat = 0;
                    }
                    $.get("societe-" + $("#idSociete").text() + "&" + $('.nbPerPage select').val() + "-" + idCat + "-" + $('#idCat').text() + ".html", function(data) {

                        //function printDataReceived(data)
                        generateData(data);

                        generatePagination();
                        setTimeout(function() {
                            $('#pagination').show();
                        }, 1000);

                        //Formulaire d'ajout un produit au panier, puis refresh du miniPanier
                        $(".formAdd2Cart").submit(function(event) {
                            event.preventDefault();
                            $.ajax({url: "societeadm.html",
                                type: "POST",
                                dataType: "json",
                                data: $(this).serialize() + "&add2Cart=1"
                            }).done(function(data) {
                                alert(data[0].message);
                                $("#previewMinicart").html(data.miniCart);
                            });
                        });

                        showProductDetails();

                        // Changer le contenu de la page avec les nouvelles données recues (celles contenues dans data) 
                    }, 'json'); //,'json' // à mettre après l'accolade
                });


                // ***************** GESTION DU MENU **************************************

                $('.menuCategorie').click(function(event) {
                    $('#idCat').html($(this).find('input[name=\"submitSearch\"]').attr("value"));
                    var $idCat = parseInt($(this).find('input[name=\"submitSearch\"]').attr("value"));

                    $("#currentCategory").html($(this).find('input[name=\"submitSearch\"]').attr("value"));
                    //$($(this)+' input[name=\"submitSearch\"]');
                    //console.log()); //.attr("value")
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

                //Fonction qui se lance lorsque l'on déroule un menu
                function postForm(element) {
                    $('#pagination').hide();
                    $("#idPage").html(1);
                    function printDataReceived(data) {
                        // Traitement et affichage des données reçues sous forme de Json : 
                        generateData(data);

                        generatePagination();
                        setTimeout(function() {
                            $('#pagination').show();
                        }, 1000);

                        //Formulaire d'ajout un produit au panier, puis refresh du miniPanier
                        $(".formAdd2Cart").submit(function(event) {
                            event.preventDefault();
                            $.ajax({url: "societeadm.html",
                                type: "POST",
                                dataType: "json",
                                data: $(this).serialize() + "&add2Cart=1"
                            }).done(function(data) {
                                alert(data[0].message);
                                $("#previewMinicart").html(data.miniCart);
                            });
                        });

                        showProductDetails();
                    }

                    $.post(ROOTPATH + 'societeadm.html', {idCategorie: $(element).children().val(), idSociete: $('#idSociete').text(), nbProduct: $('.nbPerPage select').val(), idPage: 1}, function(data) {
                        printDataReceived(data);
                    }, 'json');

                    /*$.ajax({type:"POST", data: {idCategorie: $(this).children().val() , idSociete: $('#idSociete').text()}, url:""+ROOTPATH+"societeadm.html",success: function(data){
                     printDataReceived(data)	
                     });
                     }*/
                }

                //Fonction permettant de générer l'affichage des produits et la pagination
                function generateData(data) {
                    var newContent = '<div id="right"><ul class="right_content">';
                    if (data) {
                        for (var i = 0; i < data.length; ++i) {
                            //newContent += ''+data[i].prixProduit+'<br/>';
                            //newContent += ''+data[i].quantiteProduit+'<br/>';
                            newContent += '<li style="list-style: none; width: 220px; margin: auto;float: left;">';
                            newContent += '<div class="img_index">';
                            newContent += '<img class="productImg" data-ref="' + data[i].codeProduit + '" alt="imgProduit" style="width: 128px; height: 128px;" src="img/' + data[i].imgProduit + '" />';
                            newContent += '<br/>';
                            newContent += '<span class="prixProduit">Prix: ' + data[i].prixProduit + '€</span><br/>';
                            newContent += '<span>Ref: ' + data[i].codeProduit + '</span><br/>';
                            //newContent += '<form method="POST" class="formAdd2Cart" action="societe-' + idS + '.html">';
                            newContent += 'Quantité: <select name="quantiteProduit">';
                            var o = 1;
                            do {
                                j = o * data[i].minQte;
                                newContent += '<option value="' + j + '">' + j + '</option>';
                                o++;
                            } while (j < data[i].quantiteProduit);
                            newContent += '</select>';
                            newContent += '<input type="hidden" name="idProduit" value="' + data[i].idProduit + '"><input type="hidden" name="idSociete" value="' + data[i].idSociete + '"><br/>';
                            //newContent += '<input type="submit" class="submit2Cart" name="add2Cart" value="Ajouter au panier" style="cursor: pointer;color: #fff;border: 1px solid grey;background-color: #2db3e6;height: 22px;">';
                            //newContent += '</form>';
                            newContent += '</li>';

                            var detailsProduct = '<div class="productDetails" id="productDetails' + data[i].codeProduit + '"><div class="detailsI"><center>';
                            detailsProduct += '<img style="width: 128px; height: 128px;" src="img/1393110376_Picture.png" alt="" />';
                            detailsProduct += '<div class="testRef">' + data[i].codeProduit + '</div>';
                            detailsProduct += '</center></div></div>';

                            $("#productDetails").append(detailsProduct);
                        }
                        newContent += '<div style="clear:both;"></div></ul></div><div style="clear:both;"></div>';

                        //************************ PAGINATION **************************
                        //On calcule le nombre de page nécessaire pour afficher tous les items
                        var maxPage = Math.ceil(data[0].nbProduit / $('.nbPerPage select').val());
                        if (maxPage > 4) {
                            $("#pagination").html("");
                            if (parseInt($("#idPage").text()) > 2) {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societeadm.html"><input type="hidden" name="numPage" value="1"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" value="<<"  data-value="1" /></form>');
                            }
                            if (parseInt($("#idPage").text()) > 1) {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) - 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) - 1) + '" value="<" /></form>');
                            }
                            //La boucle affiche uniquement 2 pages après et 2 pages avant
                            for (var pa = parseInt($("#idPage").text()) - 2; pa <= parseInt($("#idPage").text()) + 2; pa++) {
                                //Si c'est la 1ère page
                                if (pa >= 1) {
                                    if (pa <= (maxPage)) {
                                        if (pa == 1) {
                                            if (pa == $("#idPage").text()) {
                                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                            }
                                            else {
                                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                            }
                                        }
                                        else {
                                            if (pa == $("#idPage").text()) {
                                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                            }
                                            else {
                                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" id="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                            }
                                        }
                                    }
                                }
                            }
                            if (parseInt($("#idPage").text()) < maxPage) {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) + 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) + 1) + '" value=">" /></form>');
                            }
                            if (parseInt($("#idPage").text()) < maxPage - 1) {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + maxPage + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + maxPage + '" value=">>" /></form>');
                            }
                        }
                        else {
                            $("#pagination").html("");
                            if (parseInt($("#idPage").text()) > 2) {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societeadm.html"><input type="hidden" name="numPage" value="1"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" value="<<"  data-value="1" /></form>');
                            }
                            if (parseInt($("#idPage").text()) > 1) {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) - 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) - 1) + '" value="<" /></form>');
                            }
                            for (var p = 1; p <= maxPage; ++p) {
                                //Si c'est la 1ère page
                                if (p == 1) {
                                    if (p == $("#idPage").text()) {
                                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                                    }
                                    else {
                                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                                    }
                                }
                                else {
                                    if (p == $("#idPage").text()) {
                                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                                    }
                                    else {
                                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                                    }
                                }
                            }
                            if (parseInt($("#idPage").text()) < maxPage) {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) + 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) + 1) + '" value=">" /></form>');
                            }
                            if (parseInt($("#idPage").text()) < maxPage - 1) {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societeadm.html"><input type="hidden" name="numPage" value="' + maxPage + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + maxPage + '" value=">>" /></form>');
                            }
                        }
                    }
                    else {
                        newContent += '<center><span>Aucun Résultat</span></center><br/>';
                        newContent += '<div style="clear:both;"></div></ul></div><div style="clear:both;"></div>';
                        $("#pagination").html("");
                    }
                    $('#result').fadeOut();
                    var section = $(document.createElement("section")).css('display', 'none').html(newContent);
                    $('#result').html(section).show(50, function() {
                        section.fadeIn();
                    });
                    $('#result').fadeIn();
                }

                //Fonction permettant d'afficher les détails d'un produit lorsque l'on clique sur l'image du produit
                function showProductDetails() {
                    $(".productImg").click(function() {
                        var getRefProduct = $(this).attr("data-ref");
                        if ($("#productDetails" + getRefProduct).is(":hidden")) { /* Si le menu d'inscription est caché alors lorsque l'on clique... */
                            //$(".testRef").html(data.codeProduit);
                            $("#productDetails" + getRefProduct).slideDown("slow");  /* Permet de descendre le menu inscription */
                            $('#global').css({'position': 'fixed', 'left': '0px', 'top': '0px', 'background-color': 'black', 'height': '100%', 'width': '100%', 'z-index': '100', 'opacity': '0.7'});
                        }
                        else {
                            $("#productDetails" + getRefProduct).slideUp("slow");
                            $('#global').css({'position': 'fixed', 'left': '0px', 'top': '0px', 'background-color': '', 'height': '', 'width': '100%', 'z-index': '100', 'opacity': ''});
                        }
                    });

                    $("#global").click(function() {	/* Si l'on clique hors des menus alors, on remonte tous et on enlève l'opacité du fond */
                        $(".productDetails").slideUp("slow");
                        $('#global').css({'position': 'fixed', 'left': '0px', 'top': '0px', 'background-color': '', 'height': '', 'width': '100%', 'z-index': '100', 'opacity': ''});
                    });
                }
            </script>
        <?php
        } else {
            ?>
            <a href="<?php echo ROOTPATH; ?>/index.html">Index</a>
            <a href="<?php echo ROOTPATH; ?>/admin.html">Administration</a>
            <a href="<?php echo ROOTPATH; ?>/admin-import.html">Import</a>
            <?php
            if (isset($_GET['page']) && $_GET['page'] == "societeadm") {
                echo "> Société n°" . $_GET['param1'] . "";
                if($_GET['param3'] != "0"){
                    echo "> Ajout d'une sous catégorie";
                }
                else{
                    echo "> Ajout d'une catégorie";
                }?>
            
            <h4 class="underline"><?php
                if($_GET['param3'] != "0"){
                    echo "Ajout d'une sous catégorie";
                }
                else{
                    echo "Ajout d'une catégorie";
                }?>
            </h4>

            <form method="post" action="societeadm.<?php echo $_GET['param1']; ?>.html">
                <input type="hidden" name="AddCat_ParentId" value="<?php echo $_GET['param3']; ?>" />
                <input type="hidden" name="AddCat_SocieteId" value="<?php echo $_GET['param1']; ?>" />
                <label for="AddCat_libelleCat"> Libelle de la nouvelle <?php if($_GET['param3'] != "0"){ echo "sous";} ?> catégorie:</label>
                <input type="text" name="AddCat_libelleCat" id="AddCat_libelleCat" placeholder="nom" required/>
                <input type="submit" value="Ajouter la <?php if($_GET['param3'] != "0"){ echo "sous ";} ?>catégorie" />
            </form>
                <?php
            }
            
        }
    }
}
?>