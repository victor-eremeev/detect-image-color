<template>
	<form class="form-horizontal" @submit.prevent="onSubmit">
		<div class="card mt-3">
			<div class="card-header">
				<ul class="nav nav-tabs card-header-tabs">
				    <li class="nav-item">
				        <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">Основные</a>
				    </li>
				</ul>
			</div>
			<div class="card-body">

				<div class="tab-content" id="myTabContent">

				    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
						<div class="alert alert-success" v-show="success">Product created successfuly</div>
						<div class="form-group">
						    <label>Название</label>
						    <input type="text" class="form-control" v-model="fields.name" placeholder="Название товара">
						    <div class="alert alert-danger" v-if="errors && errors.name">{{ errors.name[0] }}</div>
						</div>
						
						<div class="form-group">
							<div class="no-img-block" v-if="!imageUrl" 
								@click.prevent="onClick" 
								@drop.prevent="onChange">
								<div class="loader-wrapper" v-if="loading">
									<div class="img-loader" style="top: 45%;"></div>
								</div>
				                <div class="no-img-icon">
				                    <i class="fa fa-camera font-10xl"></i>
				                </div>
				                <div class="no-img-title">Добавьте фото</div>
				            </div>
				            <div class="img-block" v-if="imageUrl">
				            	<div class="loader-wrapper" v-if="loading">
									<div class="img-loader" style="top: 45%;"></div>
								</div>	
				            	<img class="image" :src="imageUrl">
				            	<div class="img-crop-actions-wrap">
			    					<div class="img-crop-actions">
			    						<div class="img-crop-action-item" @click.prevent="onClick">
			  								<i class="fa fa-refresh font-sm"></i><span class="img-crop-action-text">Обновить фотографию</span>
										</div>
										<div class="img-crop-action-item" @click="deleteFile">
			  								<i class="fa fa-trash font-sm"></i><span class="img-crop-action-text">Удалить фотографию</span>
										</div>
									</div>
			  					</div>
				            </div>

		            		<input type="file" v-show="false" @change.prevent="onChange" ref="fileinput">
							<input type="hidden" name="download" v-model="download.id">
							<div class="alert alert-danger" v-show="hasError">{{ errorMsg }}</div>
                            <div class="alert alert-danger" v-if="errors && errors.download">{{ errors.download[0] }}</div>
						</div>
						
				    </div>

				</div>
				<button type="submit" class="btn btn-primary">Сохранить</button>
			</div>
		</div>
	</form>
</template>

<script>
	import { Drag, Drop } from 'vue-drag-drop';
	export default {
		props: ['path', 'productFields', 'indexPath', 'downloads', 'method'],
		data() {
			return {
				fields: !!this.productFields ? this.productFields : {},
				download: {},
				errors: {},
				loading: false,
				success: false,
				imageUrl: null,
				width: 200,
				height: 200,
				hasError: false,
				errorMsg: '',
				maxSize: 1024,
				lang: {
					error: {
						onlyImg: 'Только изображения',
						outOfSize: 'Изображение превышает предельный размер: '
					}
				}
			}
		},
		mounted() {
            
			if(!!this.downloads){
				if(this.downloads.length){
                    this.download.id = this.downloads[0].id;
                    this.imageUrl = '/storage/'+this.downloads[0].path;
                }
			}
		},
        computed: {
            request(){
                if(this.method == 'put'){
                    return axios.put;
                }
                else{
                    return axios.post;
                }
            }
        },
		components: { Drag, Drop},
		methods: {
			deleteFile(){
				axios.delete('/download/' + this.download.id)
				.then(response => {
					if(response.data.result){
						this.imageUrl = null;
						this.$refs.fileinput.value = '';
                        this.download = {};
					}
				})
			},
			onChange(event) {
                let files = event.target.files || event.dataTransfer.files;
                
                if (this.checkFile(files[0])) {
                	this.file = files[0];
                	this.uploadImage();
                }
	        },
			onClick(event) {
                if (event.target !== this.$refs.fileinput) {
                    if (document.activeElement !== this.$refs) {
                        this.$refs.fileinput.click();
                    }
                }
	        },
	        checkFile(file) {

	            if (file.type.indexOf('image') === -1) {
	                this.hasError = true;
	                this.errorMsg = this.lang.error.onlyImg;
	                return false;
	            }

	            if (file.size / 1024 > this.maxSize) {
	                this.hasError = true;
	                this.errorMsg = this.lang.error.outOfSize + this.maxSize + 'kb';
	                return false;
	            }
	            return true;
	        },
		   	async uploadImage() {
				
				let form = new FormData();
	            
	            form.append('file', this.file);
	            form.append('title', this.file.name);

	            this.loading = true;
				await axios.post('/download', form)
	            .then(response => {
                    this.download.id = response.data.id;
                    this.imageUrl = response.data.path;
                    this.loading = false;
                })
	            .catch(error => {
	                console.log(error);
	            });
			}, 
			onSubmit(){
				
				this.fields.download = this.download;
                
				this.request(this.path, this.fields)
	            .then(response => {
	            	if(response.data.id > 0){

	            		location.href = this.indexPath;
	            		/*this.success = true;
	            		this.errors = {};
	            		this.fields = {};
						this.imageUrl = null;
						this.$refs.fileinput.value = '';
	            		*/
	            	}
			     }).catch(error => {
			     	this.success = false;
			        if (error.response.status === 422) {
			        	this.errors = error.response.data.errors || {};
			        }
			     });
	    	},
		}
	}
</script>