<?php
	// Connection from http://graphite.ecs.soton.ac.uk/sparqllib/
	require_once( "sparqllib.php" );

	// SPARQL End-point
	$db = sparql_connect( "http://localhost:8171/movie-reviews/query" );

	if( !$db ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }

	// Name of Ontology
	sparql_ns( "ont","http://www.movie-reviews#" );

	//SPARQL Query
	$sparql = "SELECT (GROUP_CONCAT(DISTINCT ?genre_name; SEPARATOR=\", \") AS ?genre_names)
										(GROUP_CONCAT(DISTINCT ?director_name; SEPARATOR=\", \") AS ?director_names)
										(GROUP_CONCAT(DISTINCT ?actor_name; SEPARATOR=\", \") AS ?actor_names)
										(GROUP_CONCAT(DISTINCT ?voice_actor_name; SEPARATOR=\", \") AS ?voice_actor_names)
										?movie_name ?star_rating ?description ?classification_name
		WHERE {
			?movie a ont:Movie.
			?movie ont:Name ?movie_name.
			?movie ont:Star_rating ?star_rating.
			?movie ont:Description ?description.
			?movie ont:hasGenre ?genre.
			?genre ont:Name ?genre_name.
			?movie ont:hasDirector ?director.
			?director ont:Name ?director_name.
			OPTIONAL {?movie ont:hasActor ?actor}.
			OPTIONAL {?actor ont:Name ?actor_name}.
			OPTIONAL {?movie ont:hasVoiceActor ?voice_actor}.
			OPTIONAL {?voice_actor ont:Name ?voice_actor_name}.
			?movie ont:hasClassification ?classification.
			?classification ont:Name ?classification_name.";

	$sparql .= (isset($_POST["movie"]) && !empty($_POST["movie"]))?"FILTER(LCASE(STR(?movie_name))=\"".strtolower($_POST["movie"])."\")":"";
	$sparql .= (isset($_POST["person"]) && !empty($_POST["person"]))?"FILTER(LCASE(STR(?director_name))=\"".strtolower($_POST["person"])."\")":"";

  $sparql .= (isset($_POST["action"]) && !empty($_POST["action"]))?"FILTER(LCASE(STR(?genre_name))=\"".strtolower($_POST["action"])."\")":"";
  $sparql .= (isset($_POST["adventure"]) && !empty($_POST["adventure"]))?"FILTER(LCASE(STR(?genre_name))=\"".strtolower($_POST["adventure"])."\")":"";
	$sparql .= (isset($_POST["animation"]) && !empty($_POST["animation"]))?"FILTER(LCASE(STR(?genre_name))=\"".strtolower($_POST["animation"])."\")":"";
  $sparql .= (isset($_POST["comedy"]) && !empty($_POST["comedy"]))?"FILTER(LCASE(STR(?genre_name))=\"".strtolower($_POST["comedy"])."\")":"";
	$sparql .= (isset($_POST["crime"]) && !empty($_POST["crime"]))?"FILTER(LCASE(STR(?genre_name))=\"".strtolower($_POST["crime"])."\")":"";
  $sparql .= (isset($_POST["drama"]) && !empty($_POST["drama"]))?"FILTER(LCASE(STR(?genre_name))=\"".strtolower($_POST["drama"])."\")":"";
  $sparql .= (isset($_POST["historical"]) && !empty($_POST["historical"]))?"FILTER(LCASE(STR(?genre_name))=\"".strtolower($_POST["historical"])."\")":"";
	$sparql .= (isset($_POST["horror"]) && !empty($_POST["horror"]))?"FILTER(LCASE(STR(?genre_name))=\"".strtolower($_POST["horror"])."\")":"";
	$sparql .= (isset($_POST["romance"]) && !empty($_POST["romance"]))?"FILTER(LCASE(STR(?genre_name))=\"".strtolower($_POST["romance"])."\")":"";
  $sparql .= (isset($_POST["sci-fi"]) && !empty($_POST["sci-fi"]))?"FILTER(LCASE(STR(?genre_name))=\"".strtolower($_POST["sci-fi"])."\")":"";
	$sparql .= (isset($_POST["thriller"]) && !empty($_POST["thriller"]))?"FILTER(LCASE(STR(?genre_name))=\"".strtolower($_POST["thriller"])."\")":"";

	$sparql .= (isset($_POST["U"]) && !empty($_POST["U"]))?"FILTER(LCASE(STR(?classification_name))=\"".strtolower($_POST["U"])."\")":"";
	$sparql .= (isset($_POST["PG"]) && !empty($_POST["PG"]))?"FILTER(LCASE(STR(?classification_name))=\"".strtolower($_POST["PG"])."\")":"";
  $sparql .= (isset($_POST["12"]) && !empty($_POST["12"]))?"FILTER(LCASE(STR(?classification_name))=\"".strtolower($_POST["12"])."\")":"";
	$sparql .= (isset($_POST["15"]) && !empty($_POST["15"]))?"FILTER(LCASE(STR(?classification_name))=\"".strtolower($_POST["15"])."\")":"";
  $sparql .= (isset($_POST["18"]) && !empty($_POST["18"]))?"FILTER(LCASE(STR(?classification_name))=\"".strtolower($_POST["18"])."\")":"";

	$sparql .=	 "}";
	$sparql .= "GROUP BY ?movie_name ?star_rating ?classification_name ?description ";
  $sparql .= "ORDER BY ASC(?movie_name)";

//$sparql Results;
	$result = sparql_query( $sparql );

	if( !$result )
	{
		print sparql_errno() . ": " . sparql_error(). "\n"; exit;
	}
	$fields = sparql_field_array( $result );
?>

<html>
	<head>
		<title>Movie Reviews</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
		<link href="public/css/custom.css" rel="stylesheet">
	</head>

	<body>
		<nav class="navbar navbar-light" style="background: #d5d5d5;">
			<div class="container logo">
	  		<span class="navbar-brand mb-0 h1">Movie Reviews</span>
			</div>
		</nav>

    <!-- Template based off of https://www.bootdey.com/snippets/view/Filter-search-result-page -->
		<div class="container py-4">
			<div class="row">

				<!-- Begin Search Results -->
	  		<div class="col-md-12">
					<div class="grid search">
						<div class="grid-body">
							<div class="row">

								<!-- Begin Filters -->
								<div class="col-md-3" style="flex: 0 0 15%;">
									<h2 class="grid-title"><i class="fa fa-filter"></i> Filters</h2>
									<hr>

									<!-- Begin Filter by Genre -->
									<h4>Genres:</h4>
									<div class="checkbox">
									<form  method="POST" class="form-inline" style="margin-bottom: 8px;">
										<div class="checkbox" >
										<label><input type="checkbox" value="action" class="icheck" name="action" >&nbspAction</label>
									</div>
									</div>
									<div class="checkbox">
										<label><input type="checkbox" value="adventure" class="icheck" name="adventure"> Adventure</label>
									</div>
									<div class="checkbox">
										<label><input type="checkbox" value="animation" class="icheck" name="animation"> Animation</label>
									</div>
				          <div class="checkbox">
				            <label><input type="checkbox" value="comedy" class="icheck" name="comedy"> Comedy</label>
				          </div>
									<div class="checkbox">
										<label><input type="checkbox" value="crime" class="icheck" name="crime"> Crime</label>
									</div>
									<div class="checkbox">
										<label><input type="checkbox" value="drama" class="icheck" name="drama"> Drama</label>
									</div>
				          <div class="checkbox">
				            <label><input type="checkbox" value="historical" class="icheck" name="historical"> Historical</label>
				          </div>
				          <div class="checkbox">
				            <label><input type="checkbox" value="horror" class="icheck" name="horror"> Horror</label>
				          </div>
									<div class="checkbox">
										<label><input type="checkbox" value="romance" class="icheck" name="romance"> Romance</label>
									</div>
				          <div class="checkbox">
				            <label><input type="checkbox" value="sci-fi" class="icheck" name="sci-fi"> Science Fiction</label>
				          </div>
									<div class="checkbox">
										<label><input type="checkbox" value="thriller" class="icheck" name="thriller"> Thriller</label>
									</div>
									<!-- End Filter by Genre -->

									<div class="padding"></div>

									<!-- Begin Filter by Date-->
									<h4>By Date:</h4>
									From
									<div class="input-group date form_date" data-date="2014-06-14T05:25:07Z" data-date-format="dd-mm-yyyy" data-link-field="dtp_input1">
										<input type="text" class="form-control">
										<span class="input-group-addon bg-blue"><i class="fa fa-th"></i></span>
									</div>
									<input type="hidden" id="dtp_input1" value="">

									To
									<div class="input-group date form_date" data-date="2014-06-14T05:25:07Z" data-date-format="dd-mm-yyyy" data-link-field="dtp_input2">
										<input type="text" class="form-control">
										<span class="input-group-addon bg-blue"><i class="fa fa-th"></i></span>
									</div>
									<input type="hidden" id="dtp_input2" value="">
									<!-- End Filter by Date -->

									<div class="padding"></div>

									<!-- Begin Filter by Age Classification-->
									<h4 class="mt-2">By Rating:</h4>
									<div class="checkbox">
				            <label><input type="checkbox" value="U" class="icheck" name="U"> U</label>
				          </div>
				          <div class="checkbox">
				            <label><input type="checkbox" value="PG" class="icheck" name="PG"> PG</label>
				          </div>
									<div class="checkbox">
										<label><input type="checkbox" value="12" class="icheck" name="12"> 12</label>
									</div>
				          <div class="checkbox">
				            <label><input type="checkbox" value="15" class="icheck" name="15"> 15</label>
				          </div>
									<div class="checkbox">
										<label><input type="checkbox" value="18" class="icheck" name="18"> 18</label>
									</div>
									<!-- End Filter by Age Classification-->

									<div class="padding"></div>

									</div>
							  	<!-- End Filters -->

								<!-- Begin Results -->
								<div class="col-md-9" style="flex: 0 0 95%; max-width: 85%;">

									<!-- Begin Search Input -->
									<div class="input-group">
										<input type="text" class="form-control" style="width: 383px" name="movie" placeholder="Movies">
										<span class="input-group-btn">
											<button class="btn btn-outline-primary my-2 my-sm-0" type="submit" >Search</button>
										</span>

										<input type="text" class="form-control" style="width: 383px; margin-left: 4px;" name="person" placeholder="People">
										<span class="input-group-btn">
											<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
										</span>
										</form>
									</div>
									<!-- End Search Input -->

									<p></p>

									<div class="padding"></div>

	           			<!-- Begin Search Output -->
									<div class="row">
										<?php while($row = sparql_fetch_array($result)):?>

				              <div class="col-lg-4 col-sm-6 mb-4">
				                <div class="card h-100">
													<div class="card-img-overlay">
				                    <p class="badge badge-warning float-right" style="margin-right: -10;margin-top: -10;"><?php echo $row["star_rating"];?>/10</p>
				                  </div>
				                  <div class="card-body">
														<a class="h4" name="movie_name"><?php echo $row["movie_name"];?></a>
														<p class="card-text mb-1 mt-1"><?php echo $row["genre_names"];?></p>
														<div class="div-left">
															<a href="movie-info.php">
															<img class="mr-1 mt-1" src="public/img/<?php echo preg_replace('/\s+/', '_', $row["movie_name"]);?>.jpg" style="width:95px;height:130px;" align="left">
														</a>
														</div>
														<div class="div-rigth">
																<p style="text-align: justify"> <small>   <?php echo $row["description"];?></small> </p>
														</div>
														<p style="margin-bottom: 4px;"><small><b>Rating: </b><?php echo $row["classification_name"];?></small></p>
													 	<p style="margin-bottom: 4px;"><small><b>Directors: </b><?php echo $row["director_names"];?></small></p>
														<?php if(strlen($row["actor_names"]) < 100) : ?>
															<p style="margin-bottom: 4px;"><small> <b>Actors: </b><?php echo $row["actor_names"];?></small></p>
														<?php else : ?>
															<p style="margin-bottom: 4px;"><small> <b>Voice Actors: </b><?php echo $row["voice_actor_names"];?></small></p>
														<?php endif; ?>
				                  </div>
				                </div>
				              </div>
							 			<?php endwhile; ?>
	              	</div>

								</div>
								<!-- End Results -->

							</div>
						</div>
					</div>
				</div>
				<!-- End Search Results -->

			</div>
		</div>
	</body>
</html>
