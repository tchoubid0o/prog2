function ac_return(field, item){
	// on met en place l'expression r�guli�re
	var regex = new RegExp('[0123456789]*-idcache', 'i');
	// on l'applique au contenu
	var nomimage = regex.exec($(item).innerHTML);
	//on r�cup�re l'id
	id = nomimage[0].replace('-idcache', '');
	// et on l'affecte au champ cach�
	$(field.name+'_id').value = id;
}