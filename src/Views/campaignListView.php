<?php
if (!$GLOBALS['isLogged']) {
    header('Location: /login/authorization');
}
?>
<script>
    function deleteCampaign(id) {
        // document.getElementById("campaign_id_" + id).className += ' deleted';

        if(confirm('Are you really want to delete campaign No.' + id + '?')) {
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4) {
                    window.alert(this.response);
                    if (this.status == 200) {
                        document.getElementById("campaign_id_" + id).className += ' deleted';
                        document.getElementById("deletedResult").innerHTML = 'Deleted done'
                    }
                }
            };

            xmlhttp.open("GET", "/campaign/delete/" + id, true);
            xmlhttp.send();
        }



    }
</script>
<body>
<h1>Campaigns</h1>
<div style="container">

    <button onclick="window.location.href='/campaign/create'">
    Create new campaign
    </button>

    <?php
    $campaignsTable = "
                    <table class=\"table table-bordered\">
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Device</th>
                                <th>Geo</th>
                                <th>Url</th>
                                <th>Creating date</th>
                                <th>Actions</th>
                            </tr>";
    foreach ($data as $datum) {
        $campaignsTable .= "
                            <tr id='campaign_id_" . $datum['id'] . "' > 
                                <td>" . $datum['id'] . "</td>
                                <td>" . $datum['name'] . "</td>
                                <td>" . $datum['type'] . "</td>
                                <td>" . $datum['device'] . "</td>
                                <td>" . $datum['geo'] . "</td>
                                <td>" . $datum['url'] . "</td>
                                <td>" . $datum['when_add'] . "</td>
                            <td>
                                <img 
                                    id='" . $datum['id'] . "'
                                    src='/images/icons8-edit-32.png'
                                    alt='edit'
                                    class='icon'
                                    onclick='window.location.href=\"/campaign/edit/\"+(this.id)'>
                                <img id='" . $datum['id'] . "' 
                                    src='/images/icons8-delete-100.png' 
                                    alt='delete' 
                                    class='icon'
                                    onclick='deleteCampaign(this.id)'>
                            </td>
                            </tr>
                            ";
    }
    if (count($data) > 0) {
        echo $campaignsTable;
    } else {
        echo "No campaigns, let's create someone";
    }

    ?>
    <h2 id="deletedResult"></h2>
</div>
</body>
