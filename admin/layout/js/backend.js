$(document).ready(function() {
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
    // ++++++++++++++++++++++++++ Show/Hide Password in Password Field ++++++++++++++++++++++++++
    var passInpField = $('.password');
    $('#showPass').hover(function(){
            passInpField.attr("type","text");
        } , function(){
            passInpField.attr("type","password");
    });  
    // ++++++++++++++++++++++++++ "Confirmation Message" When Delete Member or Category ++++++++++++++++++++++++++
    // When Clicking on "Delete Button" which have class="confirm"
    $('.confirm').click( function(){
        return confirm("Are you sure you want to delete ?");
    });
    // ++++++++++++++++++++++++++ "Category View Option" ++++++++++++++++++++++++++
    $('.categories .cat h3').click( function(){
        // The Next Element of "h3" width class="full-view"
        $(this).next(".full-view").slideToggle();
    } );
    /* Panel Heading : Ordering Link && Views Span */
    $('.option span').click( function () {
        // When Clicking on "view span" , add class "active" to the clicked "span" and remove class "active" from other "span siblings"
        $(this).addClass('active').siblings('span').removeClass('active');
        // When Clicking on "span" with [ data-view="full" ] Then SlideDown Div with [ class="full-view" ] else SlideUp
        if( $(this).data("view") == "full" )
        {
            $(".cat .full-view").slideDown(200);   
        }
        else
        {
            $(".cat .full-view").slideUp(200);
        }
    });
    // ++++++++++++++++++++++++++++ Dashboard : Toggle Panel ++++++++++++++++++++++++++++
    $(".toggleInfo").click(function(){
        // if "<i></i>" hasn't [class="selected"] Then "Add it" Else "delete it"
        $(this).toggleClass("selected");
        // get the "next element" of the "parent of <i></i>" [<div class="panel-body"></div>] and SlideToggle After "100 milliseconds"
        $(this).parent().next(".panel-body").slideToggle(500);
        // if "<i></i>" has "class=selected" Then make icon "<i class='fa fa-plus'></i>" Else make icon "<i class='fa fa-minus'></i>"
        if( $(this).hasClass("selected") )
        {
            $(this).html("<i class='fa fa-plus fa-lg'></i>");
        }
        else
        {
            $(this).html("<i class='fa fa-minus fa-lg'></i>");
        }
    });
    // When Hover on "child category" Then Show "Delete Button" of "child Category"
    $(".child-link").hover(function(){
        // Show "Delete Button" of "child Category"
        $(this).find('.show-delete').fadeIn(400);
        // When "Blur" from "child category" Then Hide "Delete Button" of "child Category"
    },function(){ 
        // Hide "Delete Button" of "child Category"
        $(this).find('.show-delete').fadeOut(400);   
    }
    );

});
