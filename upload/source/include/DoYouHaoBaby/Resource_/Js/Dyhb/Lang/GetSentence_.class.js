/**
 * 向PHP 传输一条新语句
 *
 * @access private
 * @param string sSentence 语句
 * @param string sLangName 语言名字
 * @param string sPackageName 语言包名字
 * @returns string
 */
Dyhb.Lang.GetSentence_=function(sSentence,sLangName,sPackageName){
	/* 确保语言包已被载入 */
	if(typeof(Dyhb.Lang._arrLangPackage[sLangName])=='undefined' || typeof(Dyhb.Lang._arrLangPackage[sLangName][sPackageName])=='undefined'){
		Dyhb.Lang.LoadPackage_(sLangName,sPackageName);
	}

	sSentenceKey=Dyhb.Lang.MakeSentenceKey(sSentence);

	/* 保存 语言包中不存在的 新语句 */
	if(typeof(Dyhb.Lang._arrLangPackage[sLangName][sPackageName][sSentenceKey])=='undefined'){
		Dyhb.Lang._arrLangPackage[sLangName][sPackageName][sSentenceKey]=sSentence;

		/* 传递给 PHP 保存 */
		Dyhb.Lang.SaveNewSentence(sSentenceKey,sSentence,sLangName,sPackageName);
	}

	return Dyhb.Lang._arrLangPackage[sLangName][sPackageName][sSentenceKey];
};
