{% extends "base.html.twig" %}

{% block body %}
{% verbatim %}
<div id="app">
	<v-app>
		<div class="mt-2 d-flex w-100 justify-content-between align-items-center">
			<h5>Lista de produtos</h5>
			<v-btn color="primary" @click="productModal.id = 0">Criar novo</v-btn>
		</div>
		<div v-if="loading" class="w-100 d-flex align-items-center justify-content-center">
			<i class="fas fa-cog fa-spin fa-2x"></i>
			<span class="m-1"></span>
			<h5 class="m-0 p-0">Carregando...</h5>
		</div>
		<div class="mt-2">
			<v-card>
				<v-data-table :loading="loading" :items="products" :headers="[{text: '#', value: 'id'},{text: 'Nome', value: 'name'},{text: 'Criado em', value: 'created_at'},{text: 'Ações', value: 'acoes'}]">
					<template v-slot:item.acoes="{ item }">
						<v-btn @click="openModal(item)" icon>
							<v-icon>
								mdi-pencil
							</v-icon>
						</v-btn>
					</template>
				</v-data-table>
			</v-card>
			<span class="text-muted">
				*OBS: para remover um produto entre em editar produto
			</span>
		</div>


		<v-dialog :value="productModal.id != null" @input="val => val === true ? null : resetModal()" max-width="900px">
			<v-card :loading="loading">
				<v-form @submit.prevent="saveProduct" ref="productForm" lazy-validation>
					<v-card-title>
						{{ productModal.id === 0 ? 'Criar produto' : `Produto #${productModal.id}` }}
						<v-spacer></v-spacer>
						<v-btn icon @click="productModal.id = null">
							<v-icon>
								mdi-close
							</v-icon>
						</v-btn>
					</v-card-title>
					<v-divider></v-divider>
					<v-card-text>
						<v-text-field autofocus v-model="productModal.name" :rules="[v => !!v || 'Esse campo é obrigatório']" label="Nome"></v-text-field>

						<v-autocomplete v-model="productModal.tags" @input="autocompleteText = ''" :search-input.sync="autocompleteText" :rules="[v => !!v || 'Esse campo é obrigatório']" auto-select-first chips clearable deletable-chips multiple item-text="name" item-value="id" :items="tags" label="Tags"></v-autocomplete>
					</v-card-text>
					<v-divider></v-divider>
					<v-card-actions class="w-100 d-flex justify-content-between">
						<v-dialog v-if="productModal.id" v-model="dialogDeleteProduct" width="500">
							<template v-slot:activator="{ on, attrs }">
								<v-btn :disabled="loading" text type="button" color="error" v-bind="attrs" v-on="on">Remover</v-btn>
							</template>

							<v-card>
								<v-card-title>
									Confirmar ação
								</v-card-title>
								<v-card-text>
									Deseja realmente remover esse produto? Essa ação não pode ser desfeita.
								</v-card-text>
								<v-divider></v-divider>
								<v-card-actions>
									<v-spacer></v-spacer>
									<v-btn text @click="dialogDeleteProduct = false">Cancelar</v-btn>
									<v-btn text :loading="loading" color="error" @click="deleteProduct">Confirmar</v-btn>
								</v-card-actions>
							</v-card>
						</v-dialog>
						<v-spacer v-else></v-spacer>
						<v-btn :loading="loading" text type="submit" color="primary">Salvar</v-btn>
					</v-card-actions>
				</v-form>
			</v-card>
		</v-dialog>
	</v-app>
</div>
{% endverbatim %}
<script type="module" src="{{ 'js/products.js' }}"></script>
{% endblock %}