<?php
	require_once 'comments.inc.php';
	require_once 'gallery-upload.inc.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>InstagramClone Profile</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="navbar is-inline-flex is-transparent">
		<div class="navbar-brand">
			<a class="navbar-item">
				<img src="./resources/logo2.png" width="85" height="28" alt="InstagramClone">
			</a>
		</div>
		<div class="navbar-item is-flex-touch">
			<?php
				if (isset($_SESSION['id']))
				{
					echo '
						<a class="navbar-item" href="index.php?action=logout">
							<i class="material-icons">logout</i>
						</a>
						<a class="navbar-item">
							<i class="material-icons">favorite_border</i>
						</a>
						<a class="navbar-item" href="profilePage.php">
							<i class="material-icons">person_outline</i>
						</a>
					';
				}
				else
				{
					echo '
						<a class="navbar-item" href="index.php?action=logout">
							<i class="material-icons">login</i>
						</a>
					';
				}
			?>
		</div>
	</div>
	<div>
		<!-- PHP/HTML for upload picture -->
		<?php
			/* $sql = "SELECT * FROM `galleryimages`
					INNER JOIN user ON galleryimages.userid = user.id
					ORDER BY `upload_date` DESC"; */
			$sql = "SELECT COUNT(`like`.`img`) AS `likeCount`, user.id AS 'id', user.uid AS 'uid',
					user.profilePicture AS profilePicture,
					galleryimages.userid AS userid,
					galleryimages.imgFullNameGallery AS imgFullNameGallery, galleryimages.titleGallery AS titleGallery,
					galleryimages.descGallery AS descGallery,
					galleryimages.upload_date AS upload_date,
					galleryimages.idGallery AS idGallery
					FROM `galleryimages`
					LEFT JOIN `like` ON galleryimages.idGallery = `like`.`img`
					INNER JOIN user ON galleryimages.userid = user.id
					GROUP BY `galleryimages`.`idGallery`
					ORDER BY `upload_date` DESC;";
			$result = $conn->prepare($sql);
			$result->execute();
			if (!$result)
			{
				echo "SQL statement failed!";
			}
			else
			{
				$rows = $result->fetchAll();
				foreach ($rows as $row)
				{
					echo '
						<div class="columns body-columns">
							<div class="column is-half is-offset-one-quarter"> <!-- place everything to the middle -->
								<div class="card">
									<div class="header">
										<div class="media">
											<div class="media-left">
												<figure class="image is-48x48">
													<img class="is-rounded image is-48x48" src="./profile_images/'.$row['profilePicture'].'" alt="Placeholder image">
												</figure>
											</div>
											<div class="media-content">
												<a href="clicked-user-page.php?user='.$row['id'].'">
													<p class="title is-4">'.$row['uid'].'</p>
												</a>
												<p class="subtitle is-6">@'.$row['uid'].'</p>
											</div>
										</div>
									</div>
									<div class="card-image">
										<figure class="image is-1by1">
											<img class="curve" src="./user_uploads/'.$row["imgFullNameGallery"].'" alt="'.$row['titleGallery'].'">
										</figure>
									</div>
									<div class="card-content">
										<div class="level is-mobile">
											<div class="level-left">
												<div class="level-item has-text-centered">
													<a href="like.php?likeButton=1&imgId='.$row['idGallery'].'">
														<i class="material-icons">favorite_border</i>
													</a>
												</div>
												<div class="level-item has-text-centered">
													<div>
														<a href="">
															<i class="material-icons">chat_bubble_outline</i>
														</a>
													</div>
												</div>
											</div>
										</div>
										<div class="content">
											<p>
												<strong>'.$row['likeCount'].' Likes</strong>
											</p>
											<p class="title is-5">'.$row["titleGallery"].'</p>
											<p class="subtitle is-6">'.$row["descGallery"].'</p>
											<a>@bulmaio</a>.
											<a href="#">#css</a>
											<a href="#">#responsive</a>
											<br>
											<p class="subtitle is-6">'.$row['upload_date'].'</p>
										</div>
									</div>
								</div>
							</div>
						</div>';
					if (isset($_SESSION['id']))
					{

						echo	"
								<div class='columns body-columns'>
									<div class='column is-half is-offset-one-quarter'>
										<div class='card'>
											<div class='card-content'>
												<div class='content'>
													<form class='box' method='POST' action='homePage.php?action=setComments'>
														<input type='hidden' name='uid' value='".$_SESSION['id']."'>
														<input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'>
														<input type='hidden' name='imgid' value='".$row['idGallery']."'>
														<textarea class='textarea' placeholder='Add a comment . . .' name='message'></textarea><br>
														<button class='button is-hovered' type='submit' name='commentSubmit'>Comment</button>
													</form>
												</div>
												<div class='message is-success'>
													<p>You are logged in!</p>
												</div>
											</div>
										</div>
									</div>
								</div>";
					}
					else
					{
						echo "
								<div class='columns body-columns'>
									<div class='column is-half is-offset-one-quarter'>
										<div class='card'>
											<div class='card-content'>
												<div class='content'>
													<p>You need to be logged in to comment!</p>
												</div>
											</div>
										</div>
									</div>
								</div>";
					}
					getComments($conn, $row['idGallery']);
				}
			}
		?>
	</div>
	<div class="columns body-columns">
		<div class="column is-half is-offset-one-quarter"> <!-- place everything to the middle -->
			<footer class="footer">
				<div class="container is-fluid">
					<div class="content has-text-centered">
						<p>
							<i><strong>© Mango 2022 </strong> Created by Gabor Ulenius</i>
						</p>
					</div>
				</div>
			</footer>
		</div>
	</div>
</body>
</html>