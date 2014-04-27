<?php
if (isset($add2Cart['message'])) {
    echo $add2Cart['message'];
}
?>
<h4 class="underline">Catalogue de la <?php echo $nomSociete['nomSociete']; ?></h4>
<div id="idSociete" style="display: none;"><?php
    if (isset($_GET['act'])) {
        echo $_GET['act'];
    }
    ?></div>
<div id="idPage" style="display: none;"><?php
    if (isset($_GET['idPage'])) {
        echo $_GET['idPage'];
    } else {
        echo "1";
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
            $.post(ROOTPATH + 'societe.html', {idCategorie: 1, idSociete: $('#idSociete').text(), nbProduct: $('.nbPerPage select').val(), idPage: parseInt($(this).attr("data-value"))}, function(data) {
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
                        newContent += '</li>';
                    }
                    newContent += '<div style="clear:both;"></div></ul></div><div style="clear:both;"></div>';

                    //************************ PAGINATION **************************
                    //On calcule le nombre de page nécessaire pour afficher tous les items
                    var maxPage = Math.ceil(data[0].nbProduit / $('.nbPerPage select').val());
                    if (maxPage > 4) {
                        $("#pagination").html("");
                        if (parseInt($("#idPage").text()) > 2) {
                            $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="1"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" value="<<"  data-value="1" /></form>');
                        }
                        if (parseInt($("#idPage").text()) > 1) {
                            $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) - 1) + '" /><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) - 1) + '" value="<" /></form>');
                        }
                        //La boucle affiche uniquement 2 pages après et 2 pages avant
                        for (var pa = parseInt($("#idPage").text()) - 2; pa <= parseInt($("#idPage").text()) + 2; pa++) {
                            //Si c'est la 1ère page
                            if (pa >= 1) {
                                if (pa <= (maxPage)) {
                                    if (pa == 1) {
                                        if (pa == $("#idPage").text()) {
                                            $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societe.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                        }
                                        else {
                                            $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                        }
                                    }
                                    else {
                                        if (pa == $("#idPage").text()) {
                                            $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societe.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                        }
                                        else {
                                            $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                        }
                                    }
                                }
                            }
                        }
                        if (parseInt($("#idPage").text()) < maxPage) {
                            $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) + 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) + 1) + '" value=">" /></form>');
                        }
                        if (parseInt($("#idPage").text()) < maxPage - 1) {
                            $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + maxPage + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" value=">>"  data-value="' + maxPage + '" /></form>');
                        }
                    }
                    else {
                        $("#pagination").html("");
                        if (parseInt($("#idPage").text()) > 2) {
                            $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="1"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" value="<<"  data-value="1" /></form>');
                        }
                        if (parseInt($("#idPage").text()) > 1) {
                            $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) - 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) - 1) + '" value="<" /></form>');
                        }
                        for (var p = 1; p <= maxPage; ++p) {
                            //Si c'est la 1ère page
                            if (p == 1) {
                                if (p == $("#idPage").text()) {
                                    $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societe.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                                }
                                else {
                                    $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                                }
                            }
                            else {
                                if (p == $("#idPage").text()) {
                                    $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societe.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                                }
                                else {
                                    $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                                }
                            }
                        }
                        if (parseInt($("#idPage").text()) < maxPage) {
                            $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) + 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) + 1) + '" value=">" /></form>');
                        }
                        if (parseInt($("#idPage").text()) < maxPage - 1) {
                            $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + maxPage + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" value=">>"  data-value="' + maxPage + '" /></form>');
                        }
                    }
                }
                else {
                    newContent += '<center><span>Aucun Résultat</span></center><br/>';
                    newContent += '<div style="clear:both;"></div></ul></div><div style="clear:both;"></div>';
                }
                $('#result').fadeOut();
                var section = $(document.createElement("section")).css('display', 'none').html(newContent);
                $('#result').html(section).show(50, function() {
                    section.fadeIn();
                });
                $('#result').fadeIn();

                generatePagination();
                setTimeout(function() {
                    $('#pagination').show();
                }, 1000);

                //Formulaire d'ajout un produit au panier, puis refresh du miniPanier
                $(".formAdd2Cart").submit(function(event) {
                    event.preventDefault();
                    $.ajax({url: "societe.html",
                        type: "POST",
                        dataType: "json",
                        data: $(this).serialize() + "&add2Cart=1"
                    }).done(function(data) {
                        alert(data[0].message);
                        $("#previewMinicart").html(data.miniCart);
                    });
                });
            }, 'json');
        });
    }
    ;

    // ***************** Gestion nb Product par page **************************

    $('.nbPerPage select').change(function() {
        $('#pagination').hide();
        var idCat;
        if ($("#currentCategory").html().length != 0) {
            idCat = $("#currentCategory").html();
        }
        else {
            idCat = 0;
        }
        $.get("societe-" + $("#idSociete").text() + "&" + $('.nbPerPage select').val() + "-" + idCat + "-1.html", function(data) {

            //function printDataReceived(data)
            var newContent = '<div id="right"><ul class="right_content">';
            if (data) {
                console.log(data.length);
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
                    newContent += '</li>';
                }
                newContent += '<div style="clear:both;"></div></ul></div><div style="clear:both;"></div>';

                //************************ PAGINATION **************************
                //On calcule le nombre de page nécessaire pour afficher tous les items
                var maxPage = Math.ceil(data[0].nbProduit / $('.nbPerPage select').val());
                $("#idPage").html("1");
                if (maxPage > 4) {
                    $("#pagination").html("");
                    if (parseInt($("#idPage").text()) > 2) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="1"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" value="<<"  data-value="1" /></form>');
                    }
                    if (parseInt($("#idPage").text()) > 1) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) - 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) - 1) + '" value="<" /></form>');
                    }
                    //La boucle affiche uniquement 2 pages après et 2 pages avant
                    for (var pa = parseInt($("#idPage").text()) - 2; pa <= parseInt($("#idPage").text()) + 2; pa++) {
                        //Si c'est la 1ère page

                        if (pa >= 1) {
                            if (pa <= (maxPage)) {
                                if (pa == 1) {
                                    if (pa == $("#idPage").text()) {
                                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societe.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                    }
                                    else {
                                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                    }
                                }
                                else {
                                    if (pa == $("#idPage").text()) {
                                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societe.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                    }
                                    else {
                                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                    }
                                }
                            }
                        }
                    }
                    if (parseInt($("#idPage").text()) < maxPage) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) + 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) + 1) + '" value=">" /></form>');
                    }
                    if (parseInt($("#idPage").text()) < maxPage - 1) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + maxPage + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + maxPage + '" value=">>" /></form>');
                    }
                }
                else {
                    $("#pagination").html("");
                    if (parseInt($("#idPage").text()) > 2) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="1"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" value="<<"  data-value="1" /></form>');
                    }
                    if (parseInt($("#idPage").text()) > 1) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) - 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) - 1) + '" value="<" /></form>');
                    }
                    for (var p = 1; p <= maxPage; ++p) {
                        //Si c'est la 1ère page
                        if (p == 1) {
                            if (p == $("#idPage").text()) {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societe.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                            }
                            else {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                            }
                        }
                        else {
                            if (p == $("#idPage").text()) {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societe.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                            }
                            else {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                            }
                        }
                    }
                    if (parseInt($("#idPage").text()) < maxPage) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) + 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) + 1) + '" value=">" /></form>');
                    }
                    if (parseInt($("#idPage").text()) < maxPage - 1) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + maxPage + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" value=">>" data-value="' + maxPage + '"/></form>');
                    }
                }
            }
            else {
                newContent += '<center><span>Aucun Résultat</span></center><br/>';
                newContent += '<div style="clear:both;"></div></ul></div><div style="clear:both;"></div>';
            }
            $('#result').fadeOut();
            var section = $(document.createElement("section")).css('display', 'none').html(newContent);
            $('#result').html(section).show(50, function() {
                section.fadeIn();
            });
            $('#result').fadeIn();

            generatePagination();
            setTimeout(function() {
                $('#pagination').show();
            }, 1000);

            //Formulaire d'ajout un produit au panier, puis refresh du miniPanier
            $(".formAdd2Cart").submit(function(event) {
                event.preventDefault();
                $.ajax({url: "societe.html",
                    type: "POST",
                    dataType: "json",
                    data: $(this).serialize() + "&add2Cart=1"
                }).done(function(data) {
                    alert(data[0].message);
                    $("#previewMinicart").html(data.miniCart);
                });
            });

            // Changer le contenu de la page avec les nouvelles données recues (celles contenues dans data) 
        }, 'json'); //,'json' // à mettre après l'accolade
    });


    // ***************** GESTION DU MENU **************************************

    $('.menuCategorie').click(function(event) {
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

    function postForm(element) {
        $('#pagination').hide();
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
                    newContent += '</li>';
                }
                newContent += '<div style="clear:both;"></div></ul></div><div style="clear:both;"></div>';

                //************************ PAGINATION **************************
                //On calcule le nombre de page nécessaire pour afficher tous les items
                var maxPage = Math.ceil(data[0].nbProduit / $('.nbPerPage select').val());
                if (maxPage > 4) {
                    $("#pagination").html("");
                    if (parseInt($("#idPage").text()) > 2) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="1"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" value="<<"  data-value="1" /></form>');
                    }
                    if (parseInt($("#idPage").text()) > 1) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) - 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) - 1) + '" value="<" /></form>');
                    }
                    //La boucle affiche uniquement 2 pages après et 2 pages avant
                    for (var pa = parseInt($("#idPage").text()) - 2; pa <= parseInt($("#idPage").text()) + 2; pa++) {
                        //Si c'est la 1ère page

                        if (pa >= 1) {
                            console.log(maxPage);
                            if (pa <= (maxPage)) {
                                if (pa == 1) {
                                    if (pa == $("#idPage").text()) {
                                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societe.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                    }
                                    else {
                                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                    }
                                }
                                else {
                                    if (pa == $("#idPage").text()) {
                                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societe.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                    }
                                    else {
                                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + pa + '"><input type="submit" class="paginationForm" id="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + pa + '" value="' + pa + '" /></form>');
                                    }
                                }
                            }
                        }
                    }
                    if (parseInt($("#idPage").text()) < maxPage) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) + 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) + 1) + '" value=">" /></form>');
                    }
                    if (parseInt($("#idPage").text()) < maxPage - 1) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + maxPage + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + maxPage + '" value=">>" /></form>');
                    }
                }
                else {
                    $("#pagination").html("");
                    if (parseInt($("#idPage").text()) > 2) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="1"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" value="<<"  data-value="1" /></form>');
                    }
                    if (parseInt($("#idPage").text()) > 1) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) - 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) - 1) + '" value="<" /></form>');
                    }
                    for (var p = 1; p <= maxPage; ++p) {
                        //Si c'est la 1ère page
                        console.log(maxPage);
                        if (p == 1) {
                            if (p == $("#idPage").text()) {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societe.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                            }
                            else {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                            }
                        }
                        else {
                            if (p == $("#idPage").text()) {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6; background: #e6e6e6;" action="societe.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                            }
                            else {
                                $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + p + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + p + '" value="' + p + '" /></form>');
                            }
                        }
                    }
                    if (parseInt($("#idPage").text()) < maxPage) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + (parseInt($("#idPage").text()) + 1) + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + (parseInt($("#idPage").text()) + 1) + '" value=">" /></form>');
                    }
                    if (parseInt($("#idPage").text()) < maxPage - 1) {
                        $("#pagination").append('<form method="POST" id="pagination" style="float: left; border: 1px solid #c6c6c6;" action="societe.html"><input type="hidden" name="numPage" value="' + maxPage + '"><input type="submit" class="paginationForm" style="cursor: pointer; border: none; background: none;" data-value="' + maxPage + '" value=">>" /></form>');
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

            generatePagination();
            setTimeout(function() {
                $('#pagination').show();
            }, 1000);

            //Formulaire d'ajout un produit au panier, puis refresh du miniPanier
            $(".formAdd2Cart").submit(function(event) {
                event.preventDefault();
                $.ajax({url: "societe.html",
                    type: "POST",
                    dataType: "json",
                    data: $(this).serialize() + "&add2Cart=1"
                }).done(function(data) {
                    alert(data[0].message);
                    $("#previewMinicart").html(data.miniCart);
                });
            });
        }

        $.post(ROOTPATH + 'societe.html', {idCategorie: $(element).children().val(), idSociete: $('#idSociete').text(), nbProduct: $('.nbPerPage select').val(), idPage: 1}, function(data) {
            printDataReceived(data);
        }, 'json');

        /*$.ajax({type:"POST", data: {idCategorie: $(this).children().val() , idSociete: $('#idSociete').text()}, url:""+ROOTPATH+"societe.html",success: function(data){
         printDataReceived(data)	
         });
         }*/
    }

    $("#rapidSearchForm").submit(function(event) {
        event.preventDefault();
        $.ajax({url: "societe.html",
            type: "POST",
            data: $(this).serialize()
                    //On envoie le formulaire qui contient: idProduit et qteProduit
        });
    });

</script>
