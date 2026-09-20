<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ธีระภัทร เพียช่อ (ไวท์)</title>
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
</head>
<body>
   
  
    <div class="b-example-divider"></div>
  
    <nav class="py-2 bg-light border-bottom">
      <div class="container d-flex flex-wrap">
        <ul class="nav me-auto">
          <li class="nav-item"><a href="#" class="nav-link link-dark px-2 active" aria-current="page">Home</a></li>
          <li class="nav-item"><a href="#" class="nav-link link-dark px-2">Features</a></li>
          <li class="nav-item"><a href="#" class="nav-link link-dark px-2">Pricing</a></li>
          <li class="nav-item"><a href="#" class="nav-link link-dark px-2">FAQs</a></li>
          <li class="nav-item"><a href="#" class="nav-link link-dark px-2">About</a></li>
        </ul>
        <ul class="nav">
          <li class="nav-item"><a href="#" class="nav-link link-dark px-2">Login</a></li>
          <li class="nav-item"><a href="#" class="nav-link link-dark px-2">Sign up</a></li>
        </ul>
      </div>
    </nav>
    <header class="py-3 mb-4 border-bottom">
      <div class="container d-flex flex-wrap justify-content-center">
        <a href="/" class="d-flex align-items-center mb-3 mb-lg-0 me-lg-auto text-dark text-decoration-none">
          <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
          <img src="logo.png" width="60" height="50"> 
          <span class="fs-4"> LIVERPOOL </span>
        </a>
        <form class="col-12 col-lg-auto mb-3 mb-lg-0">
          <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
        </form>
      </div>
    </header>
  
    <header>
        <div class="container py-2 bg-dark text-white">
            <div id="carouselExampleCaptions"
                 class="carousel slide mx-auto"
                 data-bs-ride="carousel"
                 style="max-width: 800px;">
    
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
    
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="flo2.jpg" class="d-block w-100" alt="..." style="height:450px; object-fit:cover;">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Florian Wirtz</h5>
                        </div>
                    </div>
    
                    <div class="carousel-item">
                        <img src="sobo.webp" class="d-block w-100" alt="..." style="height:450px; object-fit:cover;">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Dominik Szoboszlai</h5>
                        </div>
                    </div>
    
                    <div class="carousel-item">
                        <img src="mac.avif" class="d-block w-100" alt="..." style="height:450px; object-fit:cover;">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Alexis Mac Allister</h5>
                        </div>
                    </div>
                </div>
    
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
    
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Next</span>
                </button>
    
            </div>
        </div>
    </header>


          <h2 class="pb-2 border-bottom"></h2>

          <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
            <div class="col">
              <div class="card card-cover h-100 overflow-hidden text-white bg-dark rounded-5 shadow-lg" style="background-image: url('unsplash-photo-1.jpg');">
                <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                  <h2 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Florian</h2>
                  <ul class="d-flex list-unstyled mt-auto">
                    <li class="me-auto">
                      <img src="flo2.jpg" alt="Bootstrap" width="200" height="200" class="rounded-circle border border-white">
                    </li>
                  </ul>
                </div>
              </div>
            </div>
      
            <div class="col">
              <div class="card card-cover h-100 overflow-hidden text-white bg-dark rounded-5 shadow-lg" style="background-image: url('unsplash-photo-2.jpg');">
                <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
                  <h2 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Szoboszlai</h2>
                  <ul class="d-flex list-unstyled mt-auto">
                    <li class="me-auto">
                      <img src="sobo.webp" alt="Bootstrap" width="200" height="200" class="rounded-circle border border-white">
                    </li>
                  </ul>
                </div>
              </div>
            </div>
      
            <div class="col">
              <div class="card card-cover h-100 overflow-hidden text-white bg-dark rounded-5 shadow-lg" style="background-image: url('unsplash-photo-3.jpg');">
                <div class="d-flex flex-column h-100 p-5 pb-3 text-shadow-1">
                  <h2 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Mac Allister</h2>
                  <ul class="d-flex list-unstyled mt-auto">
                    <li class="me-auto">
                      <img src="mac.avif" alt="Bootstrap" width="200" height="200" class="rounded-circle border border-white">
                    </li>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div> 
      
        

        
        <div class="d-flex justify-content-center align-items-start gap-4 flex-wrap p-4">
            <!-- Card 1 -->
            
<?php
$host = "localhost" ;
$usr = "root" ; 
$pwd = "" ;
$db = "8032db" ;
$conn = mysqli_connect($host,$usr,$pwd,$db) ; 

$sql = "SELECT * FROM products ";
$rs = mysqli_query($conn,$sql) ;

//echo "<h1>ธีระภัทร เพียช่อ(ไวท์)</h1>"; ?>  

<?php while($data = mysqli_fetch_array($rs)) { ?>

<div class="card" style="width: 18rem;">
    
    <img src="img/<?php echo $data['image_url']; ?>" class="card-img-top" alt="">

    <div class="card-body">
        
        <h5 class="card-title">
            <?php echo $data['product_name']; ?>
        </h5>

        <p class="card-text">
            ราคา <?php echo $data['price']; ?> บาท
        </p>

        <a href="#" class="btn btn-primary">buy</a>

    </div>
</div>

<?php } ?>
        

        <footer
        class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-5 my-5 border-top"
      >
        <div class="col mb-3">
          <a
            href="/"
            class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none"
            aria-label="Bootstrap"
          >
            <svg class="bi me-2" width="40" height="32" aria-hidden="true">
              <use xlink:href="#bootstrap"></use>
            </svg>
          </a>
          <p class="text-body-secondary">&copy; 2025</p>
        </div>
        <div class="col mb-3"></div>
        <div class="col mb-3">
          <h5>Section</h5>
          <ul class="nav flex-column">
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">Home</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">Features</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">Pricing</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">FAQs</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">About</a>
            </li>
          </ul>
        </div>
        <div class="col mb-3">
          <h5>Section</h5>
          <ul class="nav flex-column">
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">Home</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">Features</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">Pricing</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">FAQs</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">About</a>
            </li>
          </ul>
        </div>
        <div class="col mb-3">
          <h5>Section</h5>
          <ul class="nav flex-column">
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">Home</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">Features</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">Pricing</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">FAQs</a>
            </li>
            <li class="nav-item mb-2">
              <a href="#" class="nav-link p-0 text-body-secondary">About</a>
            </li>
          </ul>
        </div>
      </footer>
                
          
          
</body>
</html>