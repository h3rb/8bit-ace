<?php
 $path="../pastes/";

 function sanitize( $t ) {
 $t=urldecode($t);
 $replace = array(
"\n         ",
"\n        ",
"\n       ",
"\n      ",
"\n     ",
"\n    ",
"\n   ",
"\n  ",
"\n ",
"\n",
"\r" );
  foreach ( $replace as $r ) {
   $t=str_replace($r,"",$t);
  }
  return $t;
 }

 global $indented;
 $indented=false;
 function inde( $n ) {
  global $indented;
  $o='';
  for ( $i=0; $i<$n; $i++ ) $o.=" ";
  return "\n".$o;
  $indented=true;
 }

 function startOf( $fun, $s, $idx ) {
  $len=strlen($fun);
  $found=true;
  $f=str_split($fun);
  $j=0;
  for ( $i=$idx; $i<$idx+$len; $i++ ) {
   if ( strtolower($s[$i]) != strtolower($f[$j]) ) $found=false;
   $j++;
  }
  return $found;
 }

 function endOf( $fun, $s, $idx ) {
  $len=strlen($fun);
  $found=true;
  $f=str_split($fun);
  $j=0;
  for ( $i=$idx+$len; $i>$idx; $i-- ) {
   if ( strtolower($s[$i]) != strtolower($f[$j]) ) $found=false;
   $j++;
  }
  return $found;
 }

 function pretty( $t ) {
  $t=sanitize($t);
  $s=str_split($t);
  $indent=0;
  $inside=0;
  $Lparens=0;
  $Lbrackets=0;
  $Lcurlies=0;
  $o='';
  $wasLBracket=true;
  $wasRBracket=false;
  $wasRCurly=false;
  $wasRParen=false;
  $wasComma=false;
  global $indented;
  for( $idx=0; $idx<strlen($t); $idx++ ) {
   $ch = $s[$idx];
   switch ( $ch ) {

    case ',': {
//     if ( $wasRBracket ) $o.=",".inde($indent--);
    // else
     if ( is_numeric($s[$idx+1]) ) $o.=",";
     else
     if ( $s[$idx-1] == ',' ) $o.=",";
     else
     if ( $s[$idx+1] == ',' ) $o.=",";
     else
     if ( $s[$idx+1] == '{' ) $o.=",";
     else
     if ( $s[$idx+1] == '[' ) $o.=",";
     else
     if ( $s[$idx-1] == ']'
       || $s[$idx-1] == '}' ) $o.=",".inde($indent);
     else
     $o.=",";

     $wasComma=true;
     $wasRBracket=false;
     $wasRCurly=false;
     $wasRParen=false;
    }
    break;

    case 'r': {
     if ( $s[$idx-1] == '%' && $s[$idx+1] != ',' ) {
      $o.="r".inde($indent);
     } else
     $o.="r";
     $wasComma=false;
     $wasRBracket=false;
     $wasRCurly=false;
     $wasRParen=false;
    }
    break;

    case '(': {
      $o.="(";
      $wasRBracket=false;
      $wasRCurly=false;
      $wasRParen=false;
      $indented=false;
     if ( startOf("[null",$s,$idx-5) === true ) {
      $o.=inde($indent++);
     }
    }
    break;

    case ')': {
//     if ( $s[$idx+1] == ']' && $s[$idx+2] == ',' ) {
//      $o.=inde($indent--).")";
//      $wasRParen=true;
//     } else {
      $o.=")";
      $wasRBracket=false;
      $wasRCurly=false;
      $wasRParen=false;
      $indented=false;
//     }
    }
    break;

    case '{': {
     $wasRBracket=false;
     $wasRCurly=false;
     $wasRParen=false;
     if ( $indented === true ) $o.="{";//.inde($indent++);
     else $o.=inde($indent++)."{".inde($indent++);
    }
    break;

    case '}': {
     if ( $s[$idx+1] == ',' ) {
      $wasRBracket=false;
      $wasRCurly=true;
      $wasRParen=false;
      $o.=inde($indent-=2)."}";
     } else {
      if( $indented === true ) $o.=" }".inde($indent--);
      else $o.=inde($indent-=2)."}".inde($indent--);
//      $wasRCurly=true;
     }
    }
    break;

    case '[': {
     if ( startOf("[null",$s,$idx) === true ) {
       if ( $indented === true ) {
        $indent++;
        $o.=' [';
       } else $o.=inde($indent++).'[';
     } else
     if ( startOf("[switch",$s,$idx) === true ) {
       if ( $indented === true ) {
        $indent++;
        $o.=' [';
       } else $o.=inde($indent++).'[';
     } else
     if ( $s[$idx-1] == ']' ) {
      $o.=inde($indent)."[";
     } else
     if ( startOf("[if",$s,$idx) === true ) {
      if ( $indented === true ) {
       $indent++;
       $o.=' [';
      } else
      $o.=inde($indent++).'[';
     }
     else
     $o.='[';
 //    $o.=inde($indent++)."[";
     $wasRBracket=false;
     $wasRCurly=false;
     $wasRParen=false;
     $indented=false;
    }
    break;

    case ']': {
     if ( $s[$idx+1] == ',' ) {
      $wasRBracket=true;
      $indented=false;
      $o.="]";
     } else {
      $o.="]";//.inde($indent--);
      $indented=false;
     }
    }
    break;

    default: {
       $o.=$ch;
       $wasRBracket=false;
       $wasRCurly=false;
       $wasRParen=false;
       $indented=false;
      }
     break;
   }
  }
  return $o;
 }

 function pastes( $path="pastes/" ) { return glob($path . "*.txt"); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>MUSHcode sane-it-izer ! <?php echo $_GET['load']; ?></title>
<!-- Written by Locke@Orcs.biz for 8bitmush.org -->
<script src="http://www.google.com/jsapi"></script>
<script>
function AdjustSize() {
  var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
  var ed=document.getElementById("editor");
  var bcr=ed.getBoundingClientRect();
  var mh = mh=h-bcr.top-64;
  mh+="px";
  $("#editor").css({"height":mh});
  $("#oldpastes").css({"height":h-64});
  $("#quickref").css({"height":h-64});
}
  google.load("jquery", "1.7.1");
  google.setOnLoadCallback(function() {
  // Place init code here instead of $(document).ready()
  AdjustSize();
  $(window).on('resize',function() { AdjustSize(); });
  setTimeout(function(){hideDiv('useful');},10000);
 });
</script>
<style type="text/css">
 a { color:#0F0; }
 a:visited {color:#3F0;}
 a:hover { color:magenta;}
 .boxed { border: 4px dotted gold; overflow:hidden; color:cyan; }
 .warning { background:red; color:yellow; }
 .status { margin:0; padding:0; padding-left: 5px; background:#003; }
 .linecol { width:100px; font-family: sans-serif,arial,verdana; font-size:11px; }
 .formnum { padding:0; padding-left: 5px; }
 .totlinecol { width:120px; float:right; font-family: sans-serif,arial,verdana; font-size:11px;  }
 .pairs { width:auto; background:brown; color:yellow; }
 #controls { position: absolute; right:0; top:0; }
.fill {
  border: 1px dotted grey;
  flex: 0 1 30px;
}
</style>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Editor</title>
  <style type="text/css" media="screen">
    body {
        overflow: hidden;
        margin:0; padding:0;
        background:#226;
        color:#F93;
    }
    #editor {
        margin:0; padding:0;
        width: 100%;
        height: <?php
 echo (isset($_GET['h'])?$_GET['h']."px":"500px");
?>;
        font-size: 20px;
    }
  </style>
    <link rel="stylesheet" href="css/foundation-icons.css" />
    <link rel="stylesheet" href="css/buttons.css" />
<script type="text/javascript">
function re_pair(s) {
 var total=s.length;
 var result="";
 var lparen=0;
 var rparen=0;
 var lbrace=0;
 var rbrace=0;
 var lbracket=0;
 var rbracket=0;
 for ( i=0; i<total; i++ ) {
       if ( s.charAt(i) == '[' ) lbracket++;
  else if ( s.charAt(i) == ']' ) rbracket++;
  else if ( s.charAt(i) == '(' ) lparen++;
  else if ( s.charAt(i) == ')' ) rparen++;
  else if ( s.charAt(i) == '{' ) lbrace++;
  else if ( s.charAt(i) == '}' ) rbrace++;
 }
 result =
  ( lparen != rparen ? " (/):"+lparen+"/"+rparen : "" ) +
  ( lbrace != rbrace ? " {/}:"+lbrace+"/"+rbrace : "" ) +
  ( lbracket != rbracket ? " [/]:"+lbracket+"/"+rbracket : "" ) +
  ( lparen > rparen && lbracket > rbracket ? " missing )]?" : "" );
 var target=document.getElementById("pairs");
 target.innerHTML=result;
}
function re_status(len,pos) {
 var bar=document.getElementById("status");
 var lc=document.getElementById("linecol");
 var tlc=document.getElementById("totlinecol");
 lc.innerHTML="Line/Col <span class=\"formnum\">"+(pos.row+1)+"</span>:<span class=\"formnum\">"+(pos.column+1)+"</span>";
 tlc.innerHTML=len+" lines";
}
function urldecode(url) { return decodeURIComponent(url.replace(/\+/g, ' ')); }
function u() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) { vars[key] = urldecode(value); });
    return vars;
}
function doccode(s) {
 return (s.replace(/&#(\d+);/g, function (m, n) { return String.fromCharCode(n); })).replace("&amp;","&");
}
function copyme(o) {
//  var t="&"+(u()["attrib"])+" "+(u()["target"])+"="+(u()["sane"]);
  window.prompt ("Copy to clipboard: Ctrl+C, Enter", doccode(o.innerHTML));
}
</script>
</head>
<body>
<div id="controls">
 <a href="javascript:whenYes('index.php');" alt="new" title="New Paste!" class="buttonlink"><span class="fi-page-filled"></span></a>
 <a href="#" alt="load" title="Load Old Paste" class="buttonlink" onclick="javascript:toggleDiv('oldpastes');"><span class="fi-page-export"></span></a>
 <a href="#" alt="help" title="Mush Code Ref" class="buttonlink" onclick="javascreipt:toggleDiv('quickref');"><span class="fi-book"></span></a>
</div>
<?php
 if ( isset($_GET['load']) ) {
 $name=urldecode($_GET['load']);
 $filename=$path.$name.".txt";
 $file=file_get_contents($filename);
 $stored=filemtime($filename);
 echo "<pre>Viewing paste <i><b><span style='color:white;'>".$name."</span></i></b><br><small>made on ".date('r',$stored).'</small></pre>';
 $parse1=explode("\nContent:\n",$file);
 $parse2=explode("\n\nOne line version:\n",$parse1[1]);
 $values=explode("\n",$parse1[0]);
 $_POST['attrib']=$values[0];
 $_POST['target']=$values[1];
 $_POST['text']=$parse2[0];
}
?>
<?php
 if ( isset($_POST['text']) ) {
  if ( $_POST['pretty'] == "on" ) $_POST['text']=pretty(sanitize($_POST['text']));
  echo '<div class="boxed"><small>Sane-itized version, click to copy:</small><pre onclick="copyme(this);" style="margin:0; padding:0; background:black; color:#CDE; font-weight: bold; font-family:courier;">&amp;'
   .$_POST['attrib'].' '.$_POST['target'].'='
   .sanitize($_POST['text'])
   .'</pre></div>';
  if ( $_POST['save'] == "on" ) {
   $filename=urldecode($_POST['prefix']);
   if ( strlen($_POST['text']) > 100000 ) {
    echo '<div class="warning">Text too long to save.</div>';
   } else if ( ctype_alnum($filename) === FALSE ) {
    echo '<div class="warning">Name must be alphanumeric and a single word.</div>';
   } else if ( file_exists($path.$filename.'.txt') ) {
    echo '<div class="warning">A paste with this name already exists, pick another name.  I suggested one below.</div>';
    $savevalue=$_POST['prefix']."2";
   } else {
    file_put_contents( $path.$filename.".txt",
      urldecode($_POST['attrib'])."\n"
     .urldecode($_POST['target'])."\nContent:\n"
     .urldecode($_POST['text'])."\n\nOne line version:\n\n"
     .sanitize($_POST['text']) );
    echo '<div class="success">Pasted as <a href="'.$path.$filename.".txt".'">'.$filename.'</a></div>';
   }
  }
 }
?>
<br><b>Enter a target attribute, object reference and the mushcode.</b>
<form name="theform" id="theform" method="post" action="<?php echo "index.php" . (isset($_GET['h'])? "?h=".intval($_GET['h']):"") ?>">
 <input placeholder="some_attrib" name="attrib" value="<?php echo $_POST['attrib'] ?>" type="text"><br>
 <input placeholder="#xxxx" name="target" value="<?php echo $_POST['target'] ?>" type="text"><span style="float:right"><!--a href="index.php" target="_blank">new paste</a--></span><br>
<pre id="editor"><?php echo ($_POST['text']) ?></pre>
<script src="src/ace.js" type="text/javascript" charset="utf-8"></script>
<div style="display:none;">
 <textarea cols="79" rows="20" name="text" id="text"><?php echo ($_POST['text']) ?></textarea><br>
</div>
<div id="statusWrapper" class="status"><span id="linecol" class="linecol"></span><span id="totlinecol" class="totlinecol"></span><span id="pairs" class="pairs"> </span></div>
<script>
 var editor = ace.edit("editor");
 editor.setTheme("ace/theme/terminal");
 editor.getSession().setMode("ace/mode/mushcode");
 editor.getSession().setTabSize(1);
 var textarea = document.getElementById("text");
 editor.getSession().setValue(textarea.value);
 re_pair(textarea.value);
 editor.getSession().on('change', function(){
  textarea.value=(editor.getSession().getValue());
  re_pair(textarea.value);
 });
 function statusUpdate() {
  re_status(editor.getSession().getLength(),editor.getCursorPosition());
  setTimeout( function() { statusUpdate(); }, 50 );
 }
 setTimeout( function() {
  statusUpdate();
 }, 50 );
</script>
 <input type="hidden" name="sane" id="sane" value="empty">
 <input type="button" value="sanitize" onclick="poop()"><span class="spacer"></span>
<script>
 function flipcheck() {
  var cb=document.getElementById("save");
  cb.checked=true;
 }
 function pretty() {
 }
</script>
 <input type="checkbox" name="save" id="save"<?php if ( $_POST['save']=="on" ) echo " checked"; ?>>
 save as: <input type="text" name="prefix" placeholder="alphanumeric" id="prefix" value="<?php echo $savevalue; ?>" onkeypress="flipcheck();">
 imprettify (attempt to format for readibility): <input type="checkbox" name="pretty" id="pretty"<?php if ( $_POST['pretty']=="on" ) echo " checked"; ?>>
<script type="text/javascript">
/**
 * ReplaceAll by Fagner Brack (MIT Licensed)
 * Replaces all occurrences of a substring in a string
 */
String.prototype.replaceAll = function( token, newToken, ignoreCase ) {
 var _token; var str = this + ""; var i = -1;
 if ( typeof token === "string" ) {
  if ( ignoreCase ) { _token = token.toLowerCase();
   while( ( i = str.toLowerCase().indexOf(token, i>= 0 ? i + newToken.length : 0) ) !== -1 )
    { str = str.substring( 0, i ) + newToken + str.substring( i + token.length ); }
  } else return this.split( token ).join( newToken );
 }
 return str;
};
 function sanitize(s) {
  s=s.replaceAll( "\n         ", "" );
  s=s.replaceAll( "\n        ", "" );
  s=s.replaceAll( "\n       ", "" );
  s=s.replaceAll( "\n      ", "" );
  s=s.replaceAll( "\n     ", "" );
  s=s.replaceAll( "\n    ", "" );
  s=s.replaceAll( "\n   ", "" );
  s=s.replaceAll( "\n  ", "" );
  s=s.replaceAll( "\n ", "" );
  s=s.replaceAll( "\n", "" );
  return s;
 }
 function poop() {
  var ta=document.getElementById("text");
  var tb=document.getElementById("sane");
  tb.value=sanitize(ta.value);
  document.theform.submit();
 }
</script>
</form>
</div>
<?php if ( isset($_POST['text']) ) {
 echo '<div id="useful" onclick="javascript:hideDiv(\'useful\');" style="background:brown; border:3px solid yellow; position:absolute; left:10%; bottom:0;"><b>Found this useful?</b> Try <a href="../repeater.php" target="_blank">MUSHcode Repeater</a></div>';
} ?>
<div id="oldpastes" style="overflow: hidden; overflow-y:scroll; display:none; background:brown; padding:5px; border:2px solid red; position:absolute; top:32px; left:0; height:256px; z-index:10000;">
Old pastes:<br>
<?php
 $pasted=pastes($path);
 $lastchar=FALSE;
 echo '<div style="width:100%; display:inline-block;">';
 foreach ( $pasted as $paste ) {
  $past=str_replace($path,"",str_replace(".txt","",$paste));
  $pieces=str_split(strtolower(trim($past)));
  if ( $lastchar === FALSE ) $lastchar=ord($pieces[0]);
  if ( $lastchar !== ord($pieces[0]) ) {
   echo '</div><br><hr size=1>';
   echo '<div style="width:100%; display:inline-block;">';
  }
  $lastchar=ord($pieces[0]);
  echo
   '<span style="font-family: sans,sans-serif; font-size: 60%; border:none; float:left; display:block; padding:2px;">'.
      '<a class="smbuttonlink" target="_blank" href="index.php?load='.urlencode($past).'">'.$past.'</a>'
   .'</span>';
 }
 echo '</div>';
?>
</div>
<script type="text/javascript">
function whenYes(url) {
 var r=confirm("Create a new paste and lose whatever is in the editor?");
 if ( r === true ) {
  window.location=url;
 }
}
function hideDiv(divid){
 document.getElementById(divid).style.display = 'none';
}
function toggleDiv(divid){
if(document.getElementById(divid).style.display == 'none')
 document.getElementById(divid).style.display = 'block';
else document.getElementById(divid).style.display = 'none';
}
</script>
<div id="quickref" style="overflow-y: scroll; padding:5px; background-color:#00008A; font-weight: bolder; font-family: mono,monospace,sans-serif; color:#EFEFEF; border:3px solid orange; display:none; height:384px; position:absolute; top:32px; left:50%; z-index:10000;">
<small>
<p>
Attribute Functions:<br> DEFAULT(), EDEFAULT(), EVAL(), GET_EVAL(), GET(),
 GREP(), GREPI(), HASATTR(), HASATTRP(), HASATTRVAL(), HASATTRPVAL(), LATTR(),
 NATTR(), POSS(), UDEFAULT(), UFUN(), ULDEFAULT(), ULOCAL(), V(), XGET(), and
 ZFUN().
</p>
<p>
 Bitwise Functions:<br> BAND(), BNAND(), BNOT(), BOR(), BXOR(), SHL(), and SHR().
</p>
<p>
 Boolean Operator Functions:<br> AND(), CAND(), COR(), EQ(), GT(), GTE(), LT(),
 LTE(), NAND(), NEQ(), NOR(), NOT(), OR(), T(), and XOR().
</p>
<p>
 DBREF Functions:<br> CON(), ENTRANCES(), EXIT(), FOLLOWERS(), HOME(), LCON(),
 LEXITS(), LOC(), LOCATE(), LPARENT(), LSEARCH(), LSEARCHR(), NEXT(), NUM(),
 OWNER(), PARENT(), PMATCH(), RLOC(), RNUM(), ROOM(), WHERE(), ZONE(), WORN(),
 HELD(), and CARRIED().
</p>
<p>
Floating Point Functions:<br> ACOS(), ASIN(), ATAN(), CEIL(), COS(), E(), EXP(),
 FDIV(), FMOD(), FLOOR(), LOG(), LN(), PI(), POWER(), ROUND(), SIN(), SQRT(),
 and TAN().
</p>
<p>
 Information Functions:<br> APOSS(), ANDFLAGS(), CONN(), COMMANDSSENT(),
 CONTROLS(), DOING(), ELOCK(), FINDABLE(), FLAGS(), FULLNAME(), HASFLAG(),
 HASPOWER(), HASTYPE(), HIDDEN(), IDLE(), ISBAKER(), LOCK(), LSTATS(),
 MONEY(), WHO(), NAME(), NEARBY(), OBJ(), ORFLAGS(), PHOTO(), POLL(),
 POWERS(), PENDINGTEXT(), RECEIVEDTEXT(), RESTARTS(), RESTARTTIME(), SUBJ(),
 SHORTESTPATH(), TMONEY(), TYPE(), and VISIBLE().
</p>
<p>
 List Manipulation Functions:<br> CAT(), ELEMENT(), ELEMENTS(), EXTRACT(),
 FILTER(), FILTERBOOL(), FIRST(), FOREACH(), FOLD(), GRAB(), GRABALL(),
 INDEX(), INSERT(), ITEMIZE(), ITEMS(), ITER(), LAST(), LDELETE(), MAP(),
 MATCH(), MATCHALL(), MEMBER(), MIX(), MUNGE(), PICK(), REMOVE(), REPLACE(),
 REST(), REVWORDS(), SETDIFF(), SETINTER(), SETUNION(), SHUFFLE(), SORT(),
 SORTBY(), SPLICE(), STEP(), WORDPOS(), and WORDS().
</p>
<p>
 Math Functions:<br> ADD(), LMATH(), MAX(), MEAN(), MEDIAN(), MIN(), MUL(),
 PERCENT(), SIGN(), STDDEV(), SUB(), VAL(), and BOUND().
</p>
<p>
 Time Functions:<br> CONVSECS(), CONVUTCSECS(), CONVTIME(), CTIME(), ETIMEFMT(),
 ISDAYLIGHT(), MTIME(), SECS(), MSECS(), STARTTIME(), TIME(), TIMEFMT(),
 TIMESTRING(), and UTCTIME().
</p>
<p>
 Side-Effect Functions:<br> ATRLOCK(), CLONE(), CREATE(), COOK(), DIG(), EMIT(),
 LEMIT(), LINK(), OEMIT(), OPEN(), PEMIT(), REMIT(), SET(), TEL(), WIPE(), and
 ZEMIT().
</p>
<p>
 FRAME BUFFER Functions: FBCREATE(), FBDESTROY(), FBWRITE(), FBCLEAR(),
 FBCOPY(), FBCOPYTO(), FBCLIP(), FBDUMP(), FBFLUSH(), FBHSET(), FBLIST(), and
 FBSTATS().
</p>
<p>
 Queue Functions: QENTRIES() and QENTRY()
</p>
<p>
 Sound Functions: PLAY()
</p>
<p>
 Misc Functions:<br> ANSI(), BREAK(), C(), ASC(), DIE(), ISDBREF(), ISINT(),
 ISNUM(), ISLETTERS(), LINECOORDS(), LOCALIZE(), LNUM(), NAMESHORT(), NULL(),
 OBJEVAL(), R(), RAND(), S(), SETQ(), SETR(), SOUNDEX(), SOUNDSLIKE(),
 VALID(), VCHART(), VCHART2(), VLABEL(), and @@().
</p>
<p>
 #99 Functions:<br> BAKERDAYS(), BODYBUILD(), BOX(), CAPALL(), CATALOG(),
 CHILDREN(), CTRAILER(), DARTTIME(), DEBT(), DETAILBAR(), EXPLOREDROOM(),
 FANSITOANSI(), FANSITOXANSI(), FULLBAR(), HALFBAR(), ISDARTED(), ISNEWBIE(),
 ISWORD(), LAMBDA(), LOBJECTS(), LPLAYERS(), LTHINGS(), LVEXITS(),
 LVOBJECTS(), LVPLAYERS(), LVTHINGS(), NEWSWRAP(), NUMSUFFIX(), PLAYERSON(),
 PLAYERSTHISWEEK(), RANDOMAD(), RANDWORD(), REALRANDWORD(), REPLACECHR(),
 SECOND(), SPLITAMOUNT(), STRLENALL(), TEXT(), THIRD(), TOFANSI(), TOTALAC(),
 and UNIQUE().
</p>
<p>
 #10 Functions: GETADDRESSROOM(), LISTPROPERTYCOMM(), LISTPROPERTYRES(),
 LOTOWNER(), LOTRATING(), LOTRATINGCOUNT(), and LOTVALUE().
</p>
<p>
 #25 Functions:<br> BOUGHTPRODUCT(), COMPANYABB(), COMPANYICON(), COMPANYLIST(),
 COMPANYNAME(), COMPANYOWNERS(), COMPANYVALUE(), EMPLOYEES(), INVESTED(),
 INVESTORS(), LATESTPRODUCTSSOLD(), PRODUCTCOMPANY(), PRODUCTDESCRIPTION(),
 PRODUCTLIST(), PRODUCTNAME(), PRODUCTOWNERS(), PRODUCTRATING(),
 PRODUCTRATINGCOUNT(), PRODUCTSOLDAT(), PRODUCTTYPE(), RATEDPRODUCT(),
 SOLDPRODUCT(), TOPPRODUCTS(), TOTALSPENTONPRODUCT(), TOTALSTOCK(),
 TRANSFERMONEY(), UNIQUEBUYERCOUNT(), UNIQUEPRODUCTSBOUGHT(), and
 VALIDCOMPANY().
</p>
<p>
 #256 Functions:<br> DELETEPICTURE(), FBSAVE(), GETPICTURESECURITY(),
 HASPICTURE(), LISTPICTURES(), PICTURESIZE(), REPLACECOLOR(), RGBTOCOLOR(),
 SAVEPICTURE(), SETPICTURESECURITY(), and SHOWPICTURE().
</p>
<p>
 #123 Functions: PIECHART() and PIECHARTLABEL().
</p>
<p>
 #255 Functions: CREATEMAZE(), DRAWMAZE(), and DRAWWIREFRAME().
</p>
</small>
</div>
<div style="position:absolute; bottom:0; right:0; height:auto; z-index:22000;">
 written by <a href="http://orcs.biz" target="_blank">orcs.biz</a>
</div>
</body>
</html>
