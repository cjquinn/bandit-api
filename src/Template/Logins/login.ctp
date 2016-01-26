<?= $this->Form->create() ?>
	<?= $this->Form->input('email') ?>
	<?= $this->Form->input('password') ?>
	<?= $this->Form->input('remember_me', [
			'type' => 'checkbox'
		]) ?>
	<?= $this->Form->button('Login') ?>
<?= $this->Form->end() ?>