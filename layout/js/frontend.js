$(document).ready(function() {
    // ----------------------- Switch Between Login and Sign Up Form --------------------------------
    $(".login-page  h1  span").click(function(){
        $(this).addClass("selected").siblings().removeClass("selected");
        // Hide "login" And "signup" Form
        $(".login-page form").hide();
        // Show The Form That has the "class" equal the value of "data-class" of Clicked Link
        $("."+$(this).data("class") ).fadeIn(100);
    });
    // ++++++++++++++++++++++++++ When Focus on inputField ++++++++++++++++++++++++++
    $("[placeholder]").focus( function(){
        // store "placeholder value" on "data-text" attribute 
        $(this).attr("data-text" , $(this).attr("placeholder"));
        // set "placeholder of inputField" with "empty" 
        $(this).attr("placeholder"," ");
    }).blur( function(){
        // set "placeholder of inputField" with "data-text attribute" value
        $(this).attr("placeholder",$(this).attr("data-text")); 
    });
    // ++++++++++++++++++++++++++ Add Asterisk "*" On Required inputField ++++++++++++++++++++++++++ 
    // Select All inputFields
    $('input').each(function(){
        if( $(this).attr("required") === "required" )
        {
            // Add "*" After "Required inputField"
            $(this).after('<span class="asterisk">*</span>');
        }
    });  
    // ++++++++++++++++++++++++++ "Confirmation Message" When Delete Member or Category ++++++++++++++++++++++++++
    // When Clicking on "Delete Button" which have class="confirm"
    $('.confirm').click( function(){
        return confirm("Are you sure you want to delete ?");
    });
    // ++++++++++++++++++++++++++ "newad.php Page" : Create New Ad  ++++++++++++++++++++++++++
    // When Finish Writing in "name inputField" 
    $(".live").keyup(function(){
        if( $(this).data('class') == ".live-price")
        {
            $( $(this).data('class') ).text( "$"+$(this).val() );
        }
        // Print The value of "name inputField" inside ".live-preview .caption h3" element
        $($(this).data('class')).text($(this).val());
    });

});
 