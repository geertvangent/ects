<?php
namespace application\ehb_helpdesk;

class CreatorComponent extends Manager
{

    /**
     * Runs this component and displays its output.
     */
    function run()
    {
        $this->display_header();

        $form = new TicketForm($this);
        $form->display();


        echo '<script type=text/javascript language=javascript>
<!-- Hide Javascript on old browsers

tmp = "Useragent: '. $_SERVER['HTTP_USER_AGENT'] .'\n"
tmp += "Online:  "+navigator.onLine + "\n"
tmp += "Cookies Enabled:  "+navigator.cookieEnabled + "\n"
tmp += "Platform: "+navigator.platform + "\n"
tmp += "Cputype: "+navigator.cpuClass + "\n"
tmp += "Opsprofile: "+navigator.opsProfile + "\n"
tmp += "Systemlanguage: "+navigator.systemLanguage + "\n"
tmp += "Userlanguage: "+navigator.userLanguage + "\n"
tmp += "Userprofile: "+navigator.userProfile + "\n"

a=navigator.plugins
for (k=0; k<a.length; k++) {
tmp += "Plugin: "+ a[k].name + " - " + a[k].description  + "\n"
}

document.forms["ticket"].elements["Object-RT::Ticket--CustomField-7-Values"].value = tmp;

-->
</script>';

        $this->display_footer();
    }
}
?>