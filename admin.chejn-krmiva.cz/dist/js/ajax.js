$(document).ready(function(){

$("#zpracovavase").click(function(){
var id= $("#id_objednavky").val();
var status= "Zpracovává se";
var dataString='id_objednavky='+ id+"& status="+status;

$.ajax({
type: "POST",                      
url: "../ajax/changeStatus.php",
data: dataString,
cache: false,
success: function(data){
  location.reload();
}
});

 
});

$("#pripravujese").click(function(){
var id= $("#id_objednavky").val();
var status= "Připravuje se";
var dataString='id_objednavky='+ id+"& status="+status;

$.ajax({
type: "POST",                      
url: "../ajax/changeStatus.php",
data: dataString,
cache: false,
success: function(data){
  location.reload();
}
});


});


$("#odeslana").click(function(){
var id= $("#id_objednavky").val();
var status= "Odeslána";
var dataString='id_objednavky='+ id+"& status="+status;

$.ajax({
type: "POST",                      
url: "../ajax/changeStatus.php",
data: dataString,
cache: false,
success: function(data){
  location.reload();
}
});


});


$("#storno").click(function(){
var id= $("#id_objednavky").val();
var status= "Stornováno";
var dataString='id_objednavky='+ id+"& status="+status;


$.ajax({
type: "POST",                      
url: "../ajax/changeStatus.php",
data: dataString,
cache: false,
success: function(data){
  location.reload();
}
});

});


});
   
  