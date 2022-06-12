
//Toggle the search button
$(function() {
  $('tr.parent td span.btn')
    .on("click", function(){
    var idOfParent = $(this).parents('tr').attr('id');
    $('tr.child-'+idOfParent).toggle('slow');
  });
  $('tr[class^=child-]').hide().children('td');
});


    function inputLenghtCheck(){
       // var user_input = document.getElementById("E1input").value;
      var user_input = document.getElementsByTagName("input")[0].value;
	           if(user_input.length==8){
	               return true;
	           }else {
	               alert("Please username must be eight characters")
	               return false;
	           }
    }
	
	
