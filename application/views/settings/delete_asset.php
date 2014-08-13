<p>Vill du verkligen ta bort "<?php echo $asset['name']; ?>"?</p>
<p>
    <form action="/settings/delete_asset/<?php echo $asset['id']; ?>" method="post">
        <button type="submit" name="yes">Ja</button>
    </form>
<?php if(count($uses)) { ?>
<p>Används i: <ul>
    <?php foreach($uses as $u){ ?>
    <li style="float:none; margin: 3px;"><?php echo $u['name']; ?></li>
    <?php } ?>
</ul>
<?php } ?>
</p>