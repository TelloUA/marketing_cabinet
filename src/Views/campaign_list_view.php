<?php
if (!$GLOBALS['isLogged']) {
    header('Location: authorization');
}
?>

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
        $campaignsTable .= "<tr> 
                                            <td>" . $datum['id'] . "</td>
                                            <td>" . $datum['name'] . "</td>
                                            <td>" . $datum['type'] . "</td>
                                            <td>" . $datum['device'] . "</td>
                                            <td>" . $datum['geo'] . "</td>
                                            <td>" . $datum['url'] . "</td>
                                            <td>" . $datum['when_add'] . "</td>
                                            <td>
                                                <a href=''>
                                                    <img src='/icons/icons8-edit-32.png' alt='edit' class='icon'>
                                                </a>
                                                <img src='/icons/icons8-delete-100.png' alt='delete' class='icon'>
                                            </td>
                                        </tr>";
    }
    if (count($data) > 0) {
        echo $campaignsTable;
    } else {
        echo "No campaigns, let's create someone";
    }

    ?>
</div>