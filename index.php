<html>
<head>
<title>Conway's Game of Death</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
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

*/


function step() {
   var allItems = $('td');
   var count = 0;
   var adjacentClass;
   var item;
   var adjacentViruses;
   var adjacentAntibodies;
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
   $('td').removeClass('antibody').removeClass('virus');
   $('.antibody-add.virus-add').removeClass('antibody-add').removeClass('virus-add');
   $('.antibody-add').addClass('antibody').removeClass('antibody-add');
   $('.virus-add').addClass('virus').removeClass('virus-add');

}

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
}

table {
   margin: 30px auto;
     border-collapse: collapse;

}

td {
   border: solid black 1px;
   height: 10px;
   width: 10px;
}

.virus {
   background-color: red;
}

.antibody {
   background-color: blue;
}
</style>
</head>
<body>

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
         echo  "\t\t<td class='row$i column$j $adjacentClasses' data-column='$j' data-row='$i' ></td>\n";
      }
      echo "\t</tr>\n";
   }
?>
</table>
<button id="stepButton">Step</button>
<label><input type="checkbox" id="virusCheckbox" /> Virus?</label>
</body>
</html>
