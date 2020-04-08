<div class="py-2">
	<hr>
    <section class="pt-2 row">
    	<div class="col-md-9">
	    	<h2>{{ $channel['title'] }}</h2>
    	</div>
    	<div class="col-md-3 text-right">
	    	<button wire:click="updateVideos" class="btn btn btn-link btn-sm">Update Videos</button>
    	</div>
    </section>

    <div>
		
		@if($tags)
			<div>
				@foreach($tags as $tag)
					<span class="badge badge-secondary" >
						{{ $tag['title'] }} 
						<button wire:click="removeTag({{ $tag['id'] }})" class="btn btn-secondary btn-sm">X</button>
					</span>
				@endforeach
			</div>
		@endif

		<div class="row">
			<div class="col-md-3">
				<div class="dropdown">
					<div class="form-group">
						<label for="byTag">By tag</label>
				    	<input wire:model="search"  id="byTag" class="form-control">
					</div>
					<div class="dropdown-menu" style="display: block;padding:0;border:0">
						@if($search)
					        @livewire('tags', compact('search'))
				        @endif
					</div>
				</div>
			</div>			
			<div class="col-md-3">
				<div class="form-group">
					<label for="byFrom">Performance From</label>
			    	<input wire:model="performanceFrom"wire:keydown.enter="fetchVideos" id="byFrom" class="form-control">
				</div>				
			</div>	
			<div class="col-md-3">			
				<div class="form-group">
					<label for="byTo">Performance To</label>
			    	<input wire:model="performanceTo" wire:keydown.enter="fetchVideos" id="byTo" class="form-control">
				</div>
			</div>
		</div>



    </div>


	@if($videos)
	    <table class="table">
	    	<tr>
	    		<th style="text-align: left;">Title</th>
	    		<th style="text-align: left;">Tags</th>
	    		<th style="text-align: left;">Performance</th>
	    	</tr>
		    @foreach($videos as $video)
			    <tr>
			    	<td>{{ $video['title'] }}</td>
			    	<td>{{ $video['tag_names'] }}</td>
			    	<td class="text-right">{{ $video['performance'] }}</td>
			    </tr>
		    @endforeach
		</table>
	@endif
</div>
