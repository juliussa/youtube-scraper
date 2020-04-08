<div class="row">
	<div class="col-sm-12 col-md-6">
		<form wire:submit.prevent="addChannel">
			<div style="color:red">
				@error('newChannel') <span class="error">{{ $message }}</span> @enderror
			</div>

			<div class="form-group">
				<label for="channel">Add new channel</label>
				<input 
					type="text"
					wire:model="newChannel"
					class="form-control" 
				>
			</div>

		    <button class="btn btn-secondary btn-sm">Add channel</button>
		</form>  
	</div>
	
	<div class="col-sm-12">

	    @foreach($channels as $channel)
			@livewire('channel', compact('channel'), key($channel->id))
	    @endforeach
	</div>
 



</div>
