<?php
$username = @$_SESSION['name'];
$sql = "SELECT * FROM users WHERE fullname='$username'";
$result = mysqli_query($dbconn, $sql);
$data = mysqli_fetch_assoc($result);
?>
<div class="well menu">
    <ul class="nav nav-pills nav-stacked">
        <?php
        if ($data['p_implode'] == 1) echo '<li role="presentation"><a href="http://cvctools.com/implode">Ip\'s implode Tool</a></li>';
        if ($data['p_dnsbl'] == 1) echo '<li role="presentation"><a href="http://cvctools.com/dnsbl">Check DNSBL\'s Blocklist</a></li>';
        if ($data['p_dbl'] == 1) echo '<li role="presentation"><a href="http://cvctools.com/domainbl">Check Domains Blacklist</a></li>';
        if ($data['p_spf'] == 1) echo '<li role="presentation"><a href="http://cvctools.com/spfcheck">SPF Bulk Check Tool</a></li>';
        if ($data['p_rdns'] == 1) echo '<li role="presentation"><a href="http://cvctools.com/rdns">Bulk rDNS Check Tool</a></li>';
        if ($data['p_findsub'] == 1) echo '<li role="presentation"><a href="http://cvctools.com/findsubject">Yahoo Find Subject</a></li>';
        if ($data['p_mbc'] == 1) echo '<li role="presentation"><a href="http://cvctools.com/mbcheck">Mailboxes bulk checker</a></li>';
        if ($data['p_ipcheck'] == 1) echo '<li role="presentation"><a href="http://cvctools.com/checkips">Yahoo Ip neighbors</a></li>';
        //if($data['p_spfdb'] == 1) echo '<li role="presentation"><a href="/spfsearch">Spf Database Searche</a></li>';
        ?>
    </ul>
</div>