<template>
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">Dashboard</div>
					<div class="card-body">
						<div v-if="sessionStatus != ''">
							  {{sessionStatus}}
						</div>
						You are logged in!
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
    export default {
		props:{
			 sessionStatus: String
		},
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
