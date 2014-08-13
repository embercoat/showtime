<div class="settings">
<form action="/settings/playlist_devices" method="post">
<table>
    <thead>
    <tr>
        <th>Enhet</th>
        <?php foreach($playlists as $p){ ?>
        <th><?php echo $p['name']; ?></th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
        <?php foreach($devices as $d){ ?>
        <tr>
            <td><?php echo $d['name']; ?></td>
            <?php foreach($playlists as $p){
                $name = 'device_playlist_'.$d['device_id'].'_'.$p['idplaylist'];
                $checked = isset($d['playlists'][$p['idplaylist']]) ? 'checked="checked"' : '';
                ?>
            <td><input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="1" <?php echo $checked; ?> /></td>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>

</form>
</div>
