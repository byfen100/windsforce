/**
 * 发送一条语句
 *
 * @param string sSentence 语句
 * @param string sPackageName 语言包名字
 * @param string sLangName 语言名字
 * @returns
 */
Dyhb.Lang.Sentence=function(sSentence,sPackageName,sLangName /*=null*/){
	/* 语句只能是字符串 */
	if(typeof(sSentence)!='string'){
		throw new Error('sSentence must be a string!');
	}

	/* sLangName 缺省使用当前语言 */
	if(typeof(sLangName)=='undefined' || sLangName==null){
		sLangName=Dyhb.Lang.GetCurrentLang();
	}

	/* sPackageName 缺省使用当前语言包 */
	if(typeof(sPackageName)=='undefined' || sPackageName==null){
		sPackageName=Dyhb.Lang.GetCurrentPackageName();
	}

	/* 取得语言包中的语句 */
	sSentenceForThisLang=Dyhb.Lang.GetSentence_(sSentence,sLangName,sPackageName);

	/* 带入参数 */
	arrFormatArgs=[];
	for(nIdx=3; nIdx<arguments.length; nIdx++){
		arrFormatArgs.push(arguments[nIdx]);
	}

	if(arrFormatArgs.length){
		return Dyhb.String.Format(sSentenceForThisLang,arrFormatArgs);
	}else{
		return sSentenceForThisLang;
	}
};

Dyhb.L=Dyhb.Lang.Sentence;
