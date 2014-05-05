<div class="width800">
    <form method="post" action="OrderFinish.html" style="margin-top: 50px;">
        <label for="dateOrder" class="underline">Date de livraison souhaitée :</label>
        <input type="text" id="dateOrder" class="datepicker" name="dateOrder" required/><br/>
        <label for="commentOrder" class="underline">Commentaire :</label><br/>
        <textarea id="commentOrder" style="width: 800px;height: 150px;" name="commentOrder" required></textarea><br/>
        <div style="text-align: center;">
            <input type="submit" style="text-align: center;" value="Je valide ma commande"/>
        </div>
        <input type="hidden" name="orderConfirm" value="1"/>
    </form>    
</div>

<!-- Datepicker -->
<link rel="stylesheet" type="text/css" href="scripts/datepicker/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="scripts/datepicker/demos.css">
<script src="scripts/datepicker/jquery-1.5.1.js"></script>
<script src="scripts/datepicker/jquery.ui.core.js"></script>
<script src="scripts/datepicker/jquery.ui.widget.js"></script>
<script src="scripts/datepicker/jquery.ui.datepicker.js"></script>
<script src="scripts/datepicker/jquery.ui.datepicker-fr.js"></script>
<script>
    $(function() {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true
        });
    });
</script>