<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   杂项函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Misc_Extend{

	/**
	 * IP地址解析（Modify From Discuz!）
	 *
	 * < tinyipdata.dat 简单版 >
	 * < qqwry.dat 纯正IP数据库 >
	 */
	static public function convertIp($sIp){
		$sReturn='';

		if(preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/",$sIp)){
			$arrIp=explode('.',$sIp);

			if($arrIp[0]==10 || $arrIp[0]==127 || ($arrIp[0]==192 && $arrIp[1]==168) || ($arrIp[0]==172 && ($arrIp[1]>=16 && $arrIp[1]<=31))){
				$sReturn='- LAN';
			}elseif($arrIp[0]>255 || $arrIp[1]>255 || $arrIp[2]>255 || $arrIp[3]>255){
				$sReturn='- Invalid IP Address';
			}else{
				$sTinyipFile=WINDSFORCE_PATH.'/Public/misc/ipdata/tinyipdata.dat';
				$sFullipFile=WINDSFORCE_PATH.'/Public/misc/ipdata/qqwry.dat';

				if(is_file($sFullipFile)){
					$sReturn=self::convertipFull($sIp,$sFullipFile);
				}elseif(is_file($sTinyipFile)){
					$sReturn=self::convertipTiny($sIp,$sTinyipFile);
				}
			}
		}

		return $sReturn;
	}

	static public function convertipTiny($ip,$ipdatafile){
		static $fp=NULL,$offset=array(),$index=NULL;

		$ipdot=explode('.',$ip);
		$ip=pack('N',ip2long($ip));

		$ipdot[0]=(int)$ipdot[0];
		$ipdot[1]=(int)$ipdot[1];

		if($fp===NULL && $fp=@fopen($ipdatafile,'rb')){
			$offset=@unpack('Nlen',@fread($fp,4));
			$index=@fread($fp,$offset['len']-4);
		}elseif($fp==FALSE){
			return '- Invalid IP data file';
		}

		$length=$offset['len']-1028;
		$start=@unpack('Vlen',$index[$ipdot[0]*4].$index[$ipdot[0]*4+1].$index[$ipdot[0]*4+2].$index[$ipdot[0]*4+3]);

		for($start=$start['len']*8+1024;$start<$length;$start+=8){
			if($index{$start}.$index{$start+1}.$index{$start+2}.$index{$start+3}>=$ip){
				$index_offset=@unpack('Vlen',$index{$start+4}.$index{$start+5}.$index{$start+6}."\x0");
				$index_length=@unpack('Clen',$index{$start+7});
				break;
			}
		}

		@fseek($fp,$offset['len']+$index_offset['len']-1024);
		if($index_length['len']){
			return '- '.G::gbkToUtf8(@fread($fp,$index_length['len']),'GB2312');
		}else{
			return '- Unknown';
		}
	}

	static public function convertipFull($ip,$ipdatafile){
		if(!$fd=@fopen($ipdatafile,'rb')){
			return '- Invalid IP data file';
		}

		$ip=explode('.',$ip);
		$ipNum=$ip[0]*16777216+$ip[1]*65536+$ip[2]*256+$ip[3];

		if(!($DataBegin=fread($fd,4)) || !($DataEnd=fread($fd,4))){
			return;
		}

		@$ipbegin=implode('',unpack('L',$DataBegin));
		if($ipbegin<0){
			$ipbegin+=pow(2,32);
		}

		@$ipend=implode('',unpack('L',$DataEnd));
		if($ipend<0){
			$ipend+=pow(2,32);
		}

		$ipAllNum=($ipend-$ipbegin)/7+1;

		$BeginNum=$ip2num=$ip1num=0;
		$ipAddr1=$ipAddr2='';
		$EndNum=$ipAllNum;

		while($ip1num>$ipNum || $ip2num<$ipNum){
			$Middle=intval(($EndNum+$BeginNum)/2);

			fseek($fd,$ipbegin+7*$Middle);
			$ipData1=fread($fd,4);
			if(strlen($ipData1)<4){
				fclose($fd);
				return '- System Error';
			}

			$ip1num=implode('',unpack('L',$ipData1));
			if($ip1num<0){
				$ip1num+=pow(2,32);
			}

			if($ip1num>$ipNum){
				$EndNum=$Middle;
				continue;
			}

			$DataSeek=fread($fd,3);
			if(strlen($DataSeek)<3){
				fclose($fd);
				return '- System Error';
			}

			$DataSeek=implode('',unpack('L',$DataSeek.chr(0)));
			fseek($fd,$DataSeek);
			$ipData2=fread($fd,4);
			if(strlen($ipData2)<4){
				fclose($fd);
				return '- System Error';
			}

			$ip2num=implode('',unpack('L',$ipData2));
			if($ip2num<0){
				$ip2num+=pow(2,32);
			}

			if($ip2num<$ipNum){
				if($Middle==$BeginNum){
					fclose($fd);
					return '- Unknown';
				}
				$BeginNum=$Middle;
			}
		}

		$ipFlag=fread($fd,1);
		if($ipFlag==chr(1)){
			$ipSeek=fread($fd,3);
			if(strlen($ipSeek)<3){
				fclose($fd);
				return '- System Error';
			}

			$ipSeek=implode('',unpack('L',$ipSeek.chr(0)));
			fseek($fd,$ipSeek);
			$ipFlag=fread($fd,1);
		}

		if($ipFlag==chr(2)){
			$AddrSeek=fread($fd,3);
			if(strlen($AddrSeek)<3){
				fclose($fd);
				return '- System Error';
			}

			$ipFlag=fread($fd,1);
			if($ipFlag==chr(2)){
				$AddrSeek2=fread($fd,3);
				if(strlen($AddrSeek2)<3){
					fclose($fd);
					return '- System Error';
				}

				$AddrSeek2=implode('',unpack('L',$AddrSeek2.chr(0)));
				fseek($fd,$AddrSeek2);
			} else {
				fseek($fd,-1,SEEK_CUR);
			}

			while(($char=fread($fd,1))!=chr(0))
			$ipAddr2.=$char;

			$AddrSeek=implode('',unpack('L',$AddrSeek.chr(0)));
			fseek($fd,$AddrSeek);

			while(($char=fread($fd,1))!=chr(0))
			$ipAddr1.=$char;
		}else{
			fseek($fd,-1,SEEK_CUR);
			while(($char=fread($fd,1))!=chr(0))
			$ipAddr1.=$char;

			$ipFlag=fread($fd,1);
			if($ipFlag==chr(2)){
				$AddrSeek2=fread($fd,3);
				if(strlen($AddrSeek2)< 3){
					fclose($fd);
					return '- System Error';
				}

				$AddrSeek2=implode('',unpack('L',$AddrSeek2.chr(0)));
				fseek($fd,$AddrSeek2);
			} else {
				fseek($fd,-1,SEEK_CUR);
			}
			while(($char=fread($fd,1))!=chr(0))
			$ipAddr2.=$char;
		}
		fclose($fd);

		if(preg_match('/http/i',$ipAddr2)){
			$ipAddr2='';
		}

		$ipaddr="{$ipAddr1} {$ipAddr2}";
		$ipaddr=preg_replace('/CZ88\.NET/is','',$ipaddr);
		$ipaddr=preg_replace('/^\s*/is','',$ipaddr);
		$ipaddr=preg_replace('/\s*$/is','',$ipaddr);
		if(preg_match('/http/i',$ipaddr) || $ipaddr==''){
			$ipaddr='- Unknown';
		}

		return '- '.G::gbkToUtf8($ipaddr,'GB2312');
	}

	static public function hometag($nHometagid,$nUserid=null){
		if($nUserid===null){
			$nUserid=$GLOBALS['___login___']['user_id'];
		}
	
		$oHometagindex=HometagindexModel::F('hometag_id=? AND user_id=?',$nHometagid,$nUserid)->getOne();

		if(!empty($oHometagindex['user_id'])){
			return true;
		}else{
			return false;
		}
	}

}