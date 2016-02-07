<?= $this->Form->create($player, [
		'type' => 'file'
	]) ?>
	<?= $this->Form->input('name') ?>
	<?= $this->Form->input('login.email') ?>

	<img src="<?= $player->losing_profile_picture_url ?>">
	<?= $this->Form->input('losing_profile_picture', [
			'type' => 'file'
		]) ?>

	<img src="<?= $player->winning_profile_picture_url ?>">
	<?= $this->Form->input('winning_profile_picture', [
			'type' => 'file'
		]) ?>
	<?= $this->Form->button('Update account') ?>
<?= $this->Form->end() ?>