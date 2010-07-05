<?php echo $this->renderPartial('header'); ?>
<?php echo $this->renderPartial('menu'); ?>

<?php echo $this->addBuiltinHelpers(); ?>

<?php echo $this->tabs->render($this->object_type); ?>

<?php if (isset($this->objectlist)): ?>

<table cellspacing="0" width="100%" class="linedRow">
 <thead>
  <tr>
   <?php if ($this->allowEdit): ?>
    <th class="item" width="1%"><?php echo Horde::img('edit.png', _("Edit")) ?></th>
   <?php endif; ?>
   <?php if ($this->allowDelete): ?>
    <th class="item" width="1%"><?php echo Horde::img('delete.png', _("Delete")) ?></th>
   <?php endif; ?>
   <?php foreach ($this->attributes as $attribute => $info): ?>
     <th class="item leftAlign" width="<?php echo $info['width'] ?>%" nowrap="nowrap"><?php echo $info['title'] ?></th>
   <?php endforeach; ?>
  </tr>
 </thead>
 <tbody>
  <?php foreach ($this->objectlist as $dn => $info): ?>
  <tr>
   <?php if ($this->allowEdit): ?>
    <td>
     <?php echo $info['edit_url'] ?>
    </td>
   <?php endif; ?>
   <?php if ($this->allowDelete): ?>
    <td>
     <?php echo $info['delete_url'] ?>
    </td>
   <?php endif; ?>
   <?php foreach ($this->attributes as $attribute => $ainfo): ?>
   <td>
   <?php if (!empty($ainfo['link_view'])): ?>
   <?php echo $info['view_url'] . $this->escape($info[$attribute]) . '</a>'; ?>
   <?php else: ?>
    <?php echo $this->escape($info[$attribute]) ?>
   <?php endif; ?>
   </td>
   <?php endforeach; ?>
  </tr>
  <?php endforeach; ?>
 </tbody>
</table>
<?php endif; ?>
