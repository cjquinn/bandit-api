<?= $this->Form->create($player, [
		'type' => 'file'
	]) ?>
	<?= $this->Form->input('name') ?>
	<?= $this->Form->input('login.email') ?>
	<?php if ($player->losing_profile_picture) : ?>
		<img src="<?= $player->losing_profile_picture->url ?>">
	<?php endif; ?>
	<?= $this->Form->input('losing_profile_picture', [
			'type' => 'file'
		]) ?>
	<?php if ($player->profile_picture) : ?>
		<img src="<?= $player->profile_picture->url ?>">
	<?php endif; ?>
	<?= $this->Form->input('profile_picture', [
			'type' => 'file'
		]) ?>
	<?php if ($player->winning_profile_picture) : ?>
		<img src="<?= $player->winning_profile_picture->url ?>">
	<?php endif; ?>
	<?= $this->Form->input('winning_profile_picture', [
			'type' => 'file'
		]) ?>
	<?= $this->Form->button('Update account') ?>
<?= $this->Form->end() ?>