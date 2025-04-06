<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SRC Facilities</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="img/SRCLogoNB.png" />
  <!-- Custom CSS -->
  <style>
    :root {
      --primary-color: #3498db;
      --secondary-color: #2ecc71;
      --danger-color: #e74c3c;
      --dark-color: #2c3e50;
    }
    
    body {
      background-color: #f5f5f5;
    }
    
    .facility-card {
      transition: transform 0.3s;
      border: none;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      height: 100%;
    }
    
    .facility-card:hover {
      transform: translateY(-5px);
    }
    
    .facility-img {
      height: 200px;
      object-fit: cover;
    }
    
    .btn-see {
      background-color: #f8f9fa;
      color: var(--dark-color);
      border: 1px solid #dee2e6;
    }
    
    .btn-reserve {
      background-color: var(--secondary-color);
      color: white;
    }
    
    .btn-calendar {
      background-color: var(--primary-color);
      color: white;
    }
    
    .btn-back {
      background-color: var(--danger-color);
      color: white;
    }
    
    .navbar {
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .logo-img {
      height: 50px;
    }
    
    @media (max-width: 768px) {
      .facility-img {
        height: 180px;
      }
    }
  </style>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top py-3">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="img/SRCLogoNB.png" alt="SRC Logo" class="logo-img">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item px-2">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item px-2">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item px-2">
            <a class="nav-link" href="#">Services</a>
          </li>
          <li class="nav-item px-2">
            <a class="nav-link" href="#">Contact</a>
          </li>
          <li class="nav-item px-2">
            <a class="nav-link" href="#">Profile</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Facilities Section -->
  <section class="py-5">
    <div class="container">
      <h1 class="text-center mb-4 mb-md-5">Santa Rita College Facilities</h1>
      
      <div class="row g-4">
        <!-- Chapel -->
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="card facility-card h-100">
            <img src="img/Chapel2.jpg" class="card-img-top facility-img" alt="Chapel">
            <div class="card-body pb-2">
              <h5 class="card-title mb-3">CHAPEL</h5>
            </div>
            <div class="card-footer bg-white pt-2 pb-3">
              <div class="d-flex justify-content-between gx-2">
                <a href="#" class="btn btn-see flex-grow-1 me-2">See more</a>
                <a href="#" class="btn btn-reserve flex-grow-1">Reserve now</a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Dome -->
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="card facility-card h-100">
            <img src="img/Dome2.jpeg" class="card-img-top facility-img" alt="Dome">
            <div class="card-body pb-2">
              <h5 class="card-title mb-3">DOME</h5>
            </div>
            <div class="card-footer bg-white pt-2 pb-3">
              <div class="d-flex justify-content-between gx-2">
                <a href="#" class="btn btn-see flex-grow-1 me-2">See more</a>
                <a href="#" class="btn btn-reserve flex-grow-1">Reserve now</a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Library -->
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="card facility-card h-100">
            <img src="img/Library.jpg" class="card-img-top facility-img" alt="Library">
            <div class="card-body pb-2">
              <h5 class="card-title mb-3">LIBRARY</h5>
            </div>
            <div class="card-footer bg-white pt-2 pb-3">
              <div class="d-flex justify-content-between gx-2">
                <a href="#" class="btn btn-see flex-grow-1 me-2">See more</a>
                <a href="#" class="btn btn-reserve flex-grow-1">Reserve now</a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Computer Lab -->
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="card facility-card h-100">
            <img src="img/Comlab.jpg" class="card-img-top facility-img" alt="Computer Lab">
            <div class="card-body pb-2">
              <h5 class="card-title mb-3">COMPUTER LAB</h5>
            </div>
            <div class="card-footer bg-white pt-2 pb-3">
              <div class="d-flex justify-content-between gx-2">
                <a href="#" class="btn btn-see flex-grow-1 me-2">See more</a>
                <a href="#" class="btn btn-reserve flex-grow-1">Reserve now</a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Auditorium -->
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="card facility-card h-100">
            <img src="img/JCB.jpeg" class="card-img-top facility-img" alt="Auditorium">
            <div class="card-body pb-2">
              <h5 class="card-title mb-3">JCB</h5>
            </div>
            <div class="card-footer bg-white pt-2 pb-3">
              <div class="d-flex justify-content-between gx-2">
                <a href="#" class="btn btn-see flex-grow-1 me-2">See more</a>
                <a href="#" class="btn btn-reserve flex-grow-1">Reserve now</a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Gymnasium -->
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="card facility-card h-100">
            <img src="img/Gym.jpg" class="card-img-top facility-img" alt="Gymnasium">
            <div class="card-body pb-2">
              <h5 class="card-title mb-3">GYMNASIUM</h5>
            </div>
            <div class="card-footer bg-white pt-2 pb-3">
              <div class="d-flex justify-content-between gx-2">
                <a href="#" class="btn btn-see flex-grow-1 me-2">See more</a>
                <a href="#" class="btn btn-reserve flex-grow-1">Reserve now</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Footer Buttons -->
      <div class="d-flex flex-column flex-sm-row justify-content-center gap-3 mt-5 py-3">
        <a href="calendar.php" class="btn btn-calendar px-4 py-2">View Calendar</a>
        <a href="index.php" class="btn btn-back px-4 py-2">Back</a>
      </div>
    </div>
  </section>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>