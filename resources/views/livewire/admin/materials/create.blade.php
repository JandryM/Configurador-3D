<div>
	<form wire:submit.prevent="save" class="space-y-6">
		<div>
			<label for="name" class="block text-lg font-medium text-slate-700">Nombre</label>
			<input wire:model.defer="name" type="text" id="name" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
			@error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
		</div>

		<div>
			<label for="category_id" class="block text-lg font-medium text-slate-700">Categoría</label>
			<select wire:model.defer="category_id" id="category_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
				<option value="">Selecciona una categoría</option>
				@foreach($categories as $category)
					<option value="{{ $category->id }}">{{ $category->name }}</option>
				@endforeach
			</select>
			@error('category_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
		</div>

		<div>
			<label for="description" class="block text-lg font-medium text-slate-700">Descripción</label>
			<textarea wire:model.defer="description" id="description" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
			@error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
		</div>

		<div>
			<label class="block text-lg font-medium text-slate-700">Tipo de material</label>
			<select wire:model="material_type" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
				<option value="units">Por unidad</option>
				<option value="pieces">Por pieza</option>
				<option value="dimensions">Por dimensiones</option>
			</select>
		</div>

		@if($material_type === 'units')
			<div>
				<label for="unit_measure" class="block text-lg font-medium text-slate-700">Unidad de medida</label>
				<select wire:model.defer="unit_measure" id="unit_measure" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
					@foreach($this->availableUnits as $unit)
						<option value="{{ $unit }}">{{ ucfirst($unit) }}</option>
					@endforeach
				</select>
				@error('unit_measure') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
			</div>
			<div>
				<label for="unit_price" class="block text-lg font-medium text-slate-700">Precio unitario</label>
				<input wire:model.defer="unit_price" type="number" step="0.01" id="unit_price" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
				@error('unit_price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
			</div>
		@elseif($material_type === 'pieces')
			<div>
				<label for="unit_measure" class="block text-lg font-medium text-slate-700">Unidad de medida</label>
				<select wire:model.defer="unit_measure" id="unit_measure" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
					@foreach($this->availableUnits as $unit)
						<option value="{{ $unit }}">{{ ucfirst($unit) }}</option>
					@endforeach
				</select>
				@error('unit_measure') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
			</div>
			<div>
				<label for="piece_size" class="block text-lg font-medium text-slate-700">Tamaño de pieza</label>
				<input wire:model.defer="piece_size" type="number" step="0.001" id="piece_size" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
				@error('piece_size') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
			</div>
			<div>
				<label for="piece_price" class="block text-lg font-medium text-slate-700">Precio por pieza</label>
				<input wire:model.defer="piece_price" type="number" step="0.01" id="piece_price" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
				@error('piece_price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
			</div>
		@elseif($material_type === 'dimensions')
			<div>
				<label for="unit_measure" class="block text-lg font-medium text-slate-700">Unidad de medida</label>
				<select wire:model.defer="unit_measure" id="unit_measure" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
					@foreach($this->availableUnits as $unit)
						<option value="{{ $unit }}">{{ ucfirst($unit) }}</option>
					@endforeach
				</select>
				@error('unit_measure') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
			</div>
			<div class="grid grid-cols-2 gap-4">
				<div>
					<label for="width" class="block text-lg font-medium text-slate-700">Ancho (m)</label>
					<input wire:model.defer="width" type="number" step="0.001" id="width" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
					@error('width') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
				</div>
				<div>
					<label for="height" class="block text-lg font-medium text-slate-700">Alto (m)</label>
					<input wire:model.defer="height" type="number" step="0.001" id="height" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
					@error('height') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
				</div>
			</div>
			<div>
				<label for="unit_price" class="block text-lg font-medium text-slate-700">Precio por unidad de área</label>
				<input wire:model.defer="unit_price" type="number" step="0.01" id="unit_price" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
				@error('unit_price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
			</div>
		@endif

		<div class="pt-4">
			<button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white font-bold py-3 px-6 rounded-lg shadow-md">Guardar Material</button>
		</div>
	</form>
</div>
