<?php
require "blocks/header.php";
?>
<body>
<h1>Campaigns</h1>
<div style="container">

    <button onclick="window.location.href='/campaign/create'">
        Create new campaign
    </button>
    <?php
        if (isset($_COOKIE['id']) ) {
            // showing user campaigns
            $user_id = $_COOKIE['id'];
        } else {
            header('Location: authorization');
        }

        $userCampaignsSelect = "SELECT 
                c.`id`,
                c.`user_id`,
                c.`name`,
                c.`type`,
                c.`device`,
                g.`name` as `geo`,
                c.`limit_by_budget`,
                c.`url`,
                c.`when_add`,
                c.`when_change` 
                FROM `campaigns` c 
                LEFT JOIN `geo` g ON g.`id` = c.`geo` 
                WHERE c.`user_id` = '$user_id'";
            $userCampaigns = selectConnect($userCampaignsSelect);
            if ($userCampaigns->num_rows > 0) {
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
                while($row = $userCampaigns->fetch_assoc()) {
                    $campaignsTable .= "<tr>
                                            <td>".$row['id']."</td>
                                            <td>".$row['name']."</td>
                                            <td>".$row['type']."</td>
                                            <td>".$row['device']."</td>
                                            <td>".$row['geo']."</td>
                                            <td>".$row['url']."</td>
                                            <td>".$row['when_add']. "</td>
                                            <td>
                                                <a href=''>
                                                    <img src='/icons/icons8-edit-32.png' alt='edit' class='icon'>
                                                </a>
                                                <img src='/icons/icons8-delete-100.png' alt='delete' class='icon'>
                                            </td>
                                        </tr>";
                }
                echo $campaignsTable;
            } else {
                $noCampaigns = "No campaigns, let's create someone";
                echo $noCampaigns;
            }

    ?>




    <!--
    <?php
    //$name = $type = $geo = $device = $limit = $url = ""
    ?>
    <form action="<?php //echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="name"> Campaign name <span class="error">*</span>
            <input type="text" name="name" value="<?php //echo $name;?>" class="form-control">
        </label>
    </form>
    -->

</div>
</body>
<?php
require "blocks/footer.php";
?>