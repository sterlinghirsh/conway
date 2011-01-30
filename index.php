<html>
<head>
<title>Conway's Game of Death</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script>
// JSON!

var JSON;if(!JSON){JSON={};}(function(){"use strict";function f(n){return n<10?'0'+n:n;}if(typeof Date.prototype.toJSON!=='function'){Date.prototype.toJSON=function(key){return isFinite(this.valueOf())?this.getUTCFullYear()+'-'+f(this.getUTCMonth()+1)+'-'+f(this.getUTCDate())+'T'+f(this.getUTCHours())+':'+f(this.getUTCMinutes())+':'+f(this.getUTCSeconds())+'Z':null;};String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(key){return this.valueOf();};}var cx=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,escapable=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,gap,indent,meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'},rep;function quote(string){escapable.lastIndex=0;return escapable.test(string)?'"'+string.replace(escapable,function(a){var c=meta[a];return typeof c==='string'?c:'\\u'+('0000'+a.charCodeAt(0).toString(16)).slice(-4);})+'"':'"'+string+'"';}function str(key,holder){var i,k,v,length,mind=gap,partial,value=holder[key];if(value&&typeof value==='object'&&typeof value.toJSON==='function'){value=value.toJSON(key);}if(typeof rep==='function'){value=rep.call(holder,key,value);}switch(typeof value){case'string':return quote(value);case'number':return isFinite(value)?String(value):'null';case'boolean':case'null':return String(value);case'object':if(!value){return'null';}gap+=indent;partial=[];if(Object.prototype.toString.apply(value)==='[object Array]'){length=value.length;for(i=0;i<length;i+=1){partial[i]=str(i,value)||'null';}v=partial.length===0?'[]':gap?'[\n'+gap+partial.join(',\n'+gap)+'\n'+mind+']':'['+partial.join(',')+']';gap=mind;return v;}if(rep&&typeof rep==='object'){length=rep.length;for(i=0;i<length;i+=1){k=rep[i];if(typeof k==='string'){v=str(k,value);if(v){partial.push(quote(k)+(gap?': ':':')+v);}}}}else{for(k in value){if(Object.hasOwnProperty.call(value,k)){v=str(k,value);if(v){partial.push(quote(k)+(gap?': ':':')+v);}}}}v=partial.length===0?'{}':gap?'{\n'+gap+partial.join(',\n'+gap)+'\n'+mind+'}':'{'+partial.join(',')+'}';gap=mind;return v;}}if(typeof JSON.stringify!=='function'){JSON.stringify=function(value,replacer,space){var i;gap='';indent='';if(typeof space==='number'){for(i=0;i<space;i+=1){indent+=' ';}}else if(typeof space==='string'){indent=space;}rep=replacer;if(replacer&&typeof replacer!=='function'&&(typeof replacer!=='object'||typeof replacer.length!=='number')){throw new Error('JSON.stringify');}return str('',{'':value});};}if(typeof JSON.parse!=='function'){JSON.parse=function(text,reviver){var j;function walk(holder,key){var k,v,value=holder[key];if(value&&typeof value==='object'){for(k in value){if(Object.hasOwnProperty.call(value,k)){v=walk(value,k);if(v!==undefined){value[k]=v;}else{delete value[k];}}}}return reviver.call(holder,key,value);}text=String(text);cx.lastIndex=0;if(cx.test(text)){text=text.replace(cx,function(a){return'\\u'+('0000'+a.charCodeAt(0).toString(16)).slice(-4);});}if(/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,''))){j=eval('('+text+')');return typeof reviver==='function'?walk({'':j},''):j;}throw new SyntaxError('JSON.parse');};}}());
</script>
<script>
/*

Puzzle Mode:
 - Disable placement after first step
 - Disable placement of virus in preset levels
 - Add limit (and number indicator) for placing antibodies
 - Show message for winning or losing
 - Add no-place zone
 - add lose zone
 - add reset button
 - Level menu
 - auto-advance
 - We need puzzles

Edit mode:
 - clear button
 - load / save

2 Player:
 - ???

HTML 5:
 - <progress>
 - <input type="number" or type="range"
 
HTML 6:
 - <input type="food" or type="body-part"
 - <logarithm> (takes the log of the number between the tags e.g. <logarithm>100</logarithm> displays 10
 
HTML 7:
 - Lucky charms (</magically-delicious>
 - 

*/

var initialPosition = '';
var gameRunning = false;
var stepsLeft = 50;
var interval;


function clearBoard() {
   $('td').removeClass('antibody').removeClass('virus');
}

function loadGame(text) {
   initialPosition = text;
   clearBoard();
   if (!text)
      return;
   var gameRepresentation = JSON.parse(text);
   var viruses = gameRepresentation.viruses;
   var steps = gameRepresentation.steps;
   for (i = 0; i < viruses.length; ++i) {
      $(viruses[i]).addClass('virus');
   }
   var antibodies = gameRepresentation.antibodies;
   for (i = 0; i < antibodies.length; ++i) {
      $(antibodies[i]).addClass('antibody');
   }
   stepsLeft = steps;
}

function loadFromTextArea() {
   loadGame($('#gameData').val());
}

function loadLastGame() {
   loadGame(initialPosition);
}

// This writes the current state of the board to the textarea.
function writeGame() {
   var textArea = $('#gameData');
   var viruses = [];
   var antibodies = [];
   var allItems = $('td');
   var steps = 50;
   for (i = 0; i < allItems.length; i++) {
      item = $(allItems[i]);
      if (item.hasClass('virus'))
         viruses[viruses.length] = '#' + item.attr('id');
      if (item.hasClass('antibody'))
         antibodies[antibodies.length] = '#' + item.attr('id');
   }
   var gameRepresentation = {
      'viruses': viruses,
      'antibodies': antibodies,
      'steps': steps
   };
   var outputString = JSON.stringify(gameRepresentation);
   textArea.val(outputString);
   initialPosition = outputString;
   return outputString;
}

function step() {
   var allItems = $('td');
   var count = 0;
   var adjacentClass;
   var item;
   var adjacentViruses;
   var adjacentAntibodies;
   stepsLeft--;
   $('#stepsLeft').text(stepsLeft);
   for (i = 0; i < allItems.length; i++) {
      item = $(allItems[i]);
      adjacentClass = '.adjacentTo-' + 
       item.attr('data-column') + '-' +
       item.attr('data-row');
      adjacentViruses = $(adjacentClass + '.virus');
      adjacentAntibodies = $(adjacentClass + '.antibody');
      if (item.hasClass('antibody')) {
         // Antibody spaces with 2 or 3 neighbors survive.
         if (adjacentAntibodies.length == 2 || adjacentAntibodies.length == 3)
            item.addClass('antibody-add');
      } else if (item.hasClass('virus')) {
         // Virus spaces with antibody neighbors.
         if (adjacentAntibodies.length == 2 || adjacentAntibodies.length == 3)
            item.addClass('antibody-add');
         // Virus spaces with virus neighbors.
         else if (adjacentViruses.length == 2 || adjacentViruses.length == 3)
            item.addClass('virus-add');
      } else {
         if (adjacentAntibodies.length == 3) {
            // Empty spaces with antibody neighbors.
            item.addClass('antibody-add');
         } else if (adjacentViruses.length == 3) {
            // Empty spaces with virus neighbors.
            item.addClass('virus-add');
         }
      }
   }
   
   var viruses = $('.virus').length;
   var antibodies = $('.antibody').length;
   
   $('td').removeClass('antibody').removeClass('virus');
   $('.antibody-add.virus-add').removeClass('antibody-add').removeClass('virus-add');
   $('.antibody-add').addClass('antibody').removeClass('antibody-add');
   $('.virus-add').addClass('virus').removeClass('virus-add');
   
   if ( $('.virus').length == 0 && viruses > 0) {
      $('#winText').removeClass('hidden');
      stop();
   } else if ($('.antibody').length == 0 && antibodies > 0) {
      $('#loseText').removeClass('hidden');
      stop();
   } else if (stepsLeft == 0) {
      $('#loseText').removeClass('hidden');
      stop();
   }
}

function start() {
   stepsLeft = 50;
   writeGame();
   gameRunning = true;
   return setInterval(step,50);
}

function stop() {
   clearInterval(interval);
}

// This gets called when the DOM has loaded.
$(document).ready(function() {
   $('td').click(function(e) {
      if (!$(this).hasClass('virus') && !$(this).hasClass('antibody')) {
         if ($('#virusCheckbox').is(':checked'))
            $(this).addClass('virus');
         else
            $(this).addClass('antibody');
            
      } else {
         $(this).removeClass('virus').removeClass('antibody');
      }
   });
   $('#stepButton').click(step);
   $('#start').click(function() { interval = start(); } );
   $('#stop').click(stop);
   $('#save').click(writeGame);
   $('#load').click(loadFromTextArea);
   $('#reset').click(loadLastGame);
   $('#clear').click(clearBoard);
   
   $('td').attr('data-nextState', '');
});




</script>
<style>
table * {
   margin: 0;
   padding: 0;
   font-size: 0px;
}

body {
   text-align:center;
   background-color: black;
   color: white;
}

table {
   margin: 30px auto;
   border-collapse: collapse;
   z-index: 2;
   position: relative;
   display: float;
   cursor: pointer;
}

#bgimage {
   /*position: relative;
   top: -230px;*/
}

td {
   border: solid #111 1px;
   height: 10px;
   width: 10px;
   background-color: white;
}

.virus {
   background: red;
}

.antibody {
   background-color: blue;
}

.hidden {
   display: none;
}

#winText {
   color: green;
}

#loseText {
   color: red;
}
</style>
</head>
<body>
<h1>Conway's Game of DEATH!</h1>
<h2 id="winText" class="hidden">Win</h2>
<h2 id="loseText" class="hidden">Lose</h2>
<table>
<?php
   $rows = 20;
   $columns = 20;
   for ( $i = 0; $i < $rows ; $i++ ) {
      echo "\t<tr>\n";
      for ( $j = 0; $j < $columns ; $j++ ) {
         $adjacentRows = array($i);
         $adjacentCols = array($j);
         if ($i > 0) {
            $adjacentRows[] = $i - 1;
         }
         if ($i < $rows) {
            $adjacentRows[] = $i + 1;
         }
         if ($j > 0) {
            $adjacentCols[] = $j - 1;
         }
         if ($j < $columns) {
            $adjacentCols[] = $j + 1;
         }
         $adjacentClasses = '';
         foreach ($adjacentRows as $adjRow) {
            foreach ($adjacentCols as $adjCol) {
               if (!($adjRow == $i && $adjCol == $j))
                  $adjacentClasses .= "adjacentTo-$adjCol-$adjRow ";
            }
         }
         echo  "\t\t<td class='row$i column$j $adjacentClasses' data-column='$j' data-row='$i' id='item-$j-$i'></td>\n";
      }
      echo "\t</tr>\n";
   }
?>
</table>
<div>Steps left: <span id="stepsLeft">50</span></div>
<div>
<button id="stepButton">Step</button>
<button id="start">Start</button>
<button id="stop">Stop</button>
<button id="reset">Reset</button>
<button id="load">Load</button>
<button id="save">Save</button>
<button id="clear">Clear</button>
<textarea id="gameData"></textarea>
<input type="number" id="steps" value="50" size="5" />
<label><input type="checkbox" id="virusCheckbox" /> Bacteria?</label>
</div>
</body>
</html>
<?
/*
Level 1: (Bronze: 10 Silver: 5 Gold: 3)

{"viruses":["#item-5-15","#item-6-15","#item-7-15","#item-7-16","#item-6-17"],"antibodies":[]}

Level 2: (Bronze: 10 Silver: 7 Gold: 5)
{"viruses":["#item-3-3","#item-4-3","#item-10-3","#item-11-3","#item-4-4","#item-5-4","#item-9-4","#item-10-4","#item-1-5","#item-4-5","#item-6-5","#item-8-5","#item-10-5","#item-13-5","#item-1-6","#item-2-6","#item-3-6","#item-5-6","#item-6-6","#item-8-6","#item-9-6","#item-11-6","#item-12-6","#item-13-6","#item-2-7","#item-4-7","#item-6-7","#item-8-7","#item-10-7","#item-12-7","#item-3-8","#item-4-8","#item-5-8","#item-9-8","#item-10-8","#item-11-8","#item-3-10","#item-4-10","#item-5-10","#item-9-10","#item-10-10","#item-11-10","#item-2-11","#item-4-11","#item-6-11","#item-8-11","#item-10-11","#item-12-11","#item-1-12","#item-2-12","#item-3-12","#item-5-12","#item-6-12","#item-8-12","#item-9-12","#item-11-12","#item-12-12","#item-13-12","#item-1-13","#item-4-13","#item-6-13","#item-8-13","#item-10-13","#item-13-13","#item-4-14","#item-5-14","#item-9-14","#item-10-14","#item-3-15","#item-4-15","#item-10-15","#item-11-15"],"antibodies":[]}


Level 3: (Bronze: 17 Silver: 11 Gold: 5)
{"viruses":["#item-7-0","#item-8-0","#item-11-0","#item-12-0","#item-0-1","#item-1-1","#item-2-1","#item-6-1","#item-8-1","#item-11-1","#item-17-1","#item-18-1","#item-19-1","#item-12-2","#item-14-2","#item-4-3","#item-6-3","#item-14-4","#item-16-4","#item-2-5","#item-4-5","#item-16-6","#item-18-6","#item-0-7","#item-2-7","#item-19-7","#item-0-8","#item-1-8","#item-18-8","#item-19-8"],"antibodies":[]}


Level 4: (Bronze: 15 Silver: 10 Gold: 5)
{"viruses":["#item-2-2","#item-2-3","#item-2-4","#item-2-6","#item-2-7","#item-2-8","#item-2-10","#item-2-11","#item-2-12","#item-2-14","#item-2-15","#item-2-16"],"antibodies":[]}

*/
?>