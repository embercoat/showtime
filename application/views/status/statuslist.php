<div class="settings">
    <table>
        <thead>
        <tr>
            <th style="width: 250px">Enhet</th>
            <th style="width: 200px">Address</th>
            <th style="width: 200px">Status</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach($devices as $d){ ?>
            <tr class="device">
                <td><?php echo $d['name']; ?></td>
                <td class="address"><?php echo $d['address']; ?></td>
                <td class="status" id="device_status_<?php echo $d['device_id']; ?>"></td>
            <?php } ?>
        </tbody>
    </table>
</div>
