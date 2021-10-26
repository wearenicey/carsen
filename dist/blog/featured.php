

				<cms:template title='Featured' executable='0' >
					<cms:editable name='f_image' type='image' />
					<cms:editable name='f_heading' type='text' />
					<cms:editable name='f_link' type='text' />
				</cms:template>

				<div class="jumbotron shadow-lg p-4 p-md-5 text-white rounded"
						 style="background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url('<cms:show f_image />');
										background-repeat: no-repeat;
										background-size: cover;
										background-position: center center;">

					<div class="col-md-10 px-0">
						<h1 class="display-4"><cms:show f_heading /></h1>
						<p class="lead mb-0"><a href="<cms:show f_link />" class="text-white font-weight-bold">Continue reading...</a></p>
					</div>
				</div>

