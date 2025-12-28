<?php
// Include the header
include 'header.php';
?>

<main id="main" class="mt-5 pt-5">
<style>
.gallery-item img {
    width: 100%;
    height: 250px; /* Adjust height as needed */
    object-fit: cover; /* Ensures image fills the container without stretching */
    border-radius: 8px; /* Optional: keeps rounded corners */
}
</style>

  <!-- Page Title -->
  <div class="page-title">
    <div class="heading">
      <div class="container">
        <div class="row d-flex justify-content-center text-center">
          <div class="col-lg-8">
            <h1 class="custom-bold-primary">Gallery</h1>
            <p class="mb-0"></p>
          </div>
        </div>
      </div>
    </div>
  </div><!-- End Page Title -->

  <!-- Departments Tabs Section -->
  <section id="departments-tabs" class="departments-tabs section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="medical-specialties">
        <div class="row">
          <div class="col-12">
            <div class="specialty-navigation">
              <div class="nav nav-pills d-flex" id="specialty-tabs" role="tablist" data-aos="fade-up" data-aos-delay="400">
                <a class="nav-link department-tab active" id="asphyxia-tab" data-bs-toggle="pill" href="#tab-asphyxia" role="tab" aria-controls="tab-asphyxia" aria-selected="true">Asphyxia</a>
                <a class="nav-link department-tab" id="firearm-tab" data-bs-toggle="pill" href="#tab-firearm" role="tab" aria-controls="tab-firearm" aria-selected="false">Firearm Injuries</a>
                <a class="nav-link department-tab" id="blunt-tab" data-bs-toggle="pill" href="#tab-blunt" role="tab" aria-controls="tab-blunt" aria-selected="false">Blunt Force Injuries</a>
                <a class="nav-link department-tab" id="sharp-tab" data-bs-toggle="pill" href="#tab-sharp" role="tab" aria-controls="tab-sharp" aria-selected="false">Sharp Force Injuries</a>
                <a class="nav-link department-tab" id="postmortem-tab" data-bs-toggle="pill" href="#tab-postmortem" role="tab" aria-controls="tab-postmortem" aria-selected="false">Postmortem Changes</a>
                <a class="nav-link department-tab" id="suddendeath-tab" data-bs-toggle="pill" href="#tab-suddendeath" role="tab" aria-controls="tab-suddendeath" aria-selected="false">Sudden Deaths</a>
                <a class="nav-link department-tab" id="thermal-tab" data-bs-toggle="pill" href="#tab-thermal" role="tab" aria-controls="tab-thermal" aria-selected="false">Thermal Injuries</a>
                <a class="nav-link department-tab" id="others-tab" data-bs-toggle="pill" href="#tab-others" role="tab" aria-controls="tab-others" aria-selected="false">Others</a>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="tab-content department-content" id="specialty-content" data-aos="fade-up" data-aos-delay="500">

              <!-- Asphyxia Gallery -->
              <div class="tab-pane fade show active" id="tab-asphyxia" role="tabpanel" aria-labelledby="asphyxia-tab">
                <div class="row g-4 mt-4">
                  <?php
                  for ($i = 1; $i <= 12; $i++) {
                      echo '<div class="col-md-4">
                              <div class="gallery-item">
                                <img src="assets/img/axp'.$i.'.webp" alt="Asphyxia '.$i.'" class="img-fluid rounded">
                              </div>
                            </div>';
                  }
                  ?>
                </div>
              </div>

              <!-- Firearm Injuries Gallery -->
              <div class="tab-pane fade" id="tab-firearm" role="tabpanel" aria-labelledby="firearm-tab">
                <div class="row g-4 mt-4">
                  <?php
                  for ($i = 1; $i <= 12; $i++) {
                      echo '<div class="col-md-4">
                              <div class="gallery-item">
                                <img src="assets/img/F'.$i.'.webp" alt="Firearm Injuries '.$i.'" class="img-fluid rounded">
                              </div>
                            </div>';
                  }
                  ?>
                </div>
              </div>

              <!-- Blunt Force Injuries Gallery -->
              <div class="tab-pane fade" id="tab-blunt" role="tabpanel" aria-labelledby="blunt-tab">
                <div class="row g-4 mt-4">
                  <?php
                  for ($i = 1; $i <= 12; $i++) {
                      echo '<div class="col-md-4">
                              <div class="gallery-item">
                                <img src="assets/img/B'.$i.'.webp" alt="Blunt Force Injuries '.$i.'" class="img-fluid rounded">
                              </div>
                            </div>';
                  }
                  ?>
                </div>
              </div>

              <!-- Sharp Force Injuries Gallery -->
              <div class="tab-pane fade" id="tab-sharp" role="tabpanel" aria-labelledby="sharp-tab">
                <div class="row g-4 mt-4">
                  <?php
                  for ($i = 1; $i <= 12; $i++) {
                      echo '<div class="col-md-4">
                              <div class="gallery-item">
                                <img src="assets/img/SH'.$i.'.webp" alt="Sharp Force Injuries '.$i.'" class="img-fluid rounded">
                              </div>
                            </div>';
                  }
                  ?>
                </div>
              </div>

              <!-- Postmortem Changes Gallery -->
              <div class="tab-pane fade" id="tab-postmortem" role="tabpanel" aria-labelledby="postmortem-tab">
                <div class="row g-4 mt-4">
                  <?php
                  for ($i = 1; $i <= 12; $i++) {
                      echo '<div class="col-md-4">
                              <div class="gallery-item">
                                <img src="assets/img/P'.$i.'.webp" alt="Postmortem '.$i.'" class="img-fluid rounded">
                              </div>
                            </div>';
                  }
                  ?>
                </div>
              </div>
              <!-- Sudden Deaths Changes Gallery -->
              <div class="tab-pane fade" id="tab-suddendeath" role="tabpanel" aria-labelledby="suddendeath-tab">
                <div class="row g-4 mt-4">
                  <?php
                  for ($i = 1; $i <= 12; $i++) {
                      echo '<div class="col-md-4">
                              <div class="gallery-item">
                                <img src="assets/img/S'.$i.'.webp" alt="Sudden Deaths '.$i.'" class="img-fluid rounded">
                              </div>
                            </div>';
                  }
                  ?>
                </div>
              </div>

              <!-- Thermal Injuries Changes Gallery -->
              <div class="tab-pane fade" id="tab-thermal" role="tabpanel" aria-labelledby="thermal-tab">
                <div class="row g-4 mt-4">
                  <?php
                  for ($i = 1; $i <= 12; $i++) {
                      echo '<div class="col-md-4">
                              <div class="gallery-item">
                                <img src="assets/img/T'.$i.'.webp" alt="Thermal Injuries '.$i.'" class="img-fluid rounded">
                              </div>
                            </div>';
                  }
                  ?>
                </div>
              </div>

              <!-- Others Changes Gallery -->
              <div class="tab-pane fade" id="tab-others" role="tabpanel" aria-labelledby="others-tab">
                <div class="row g-4 mt-4">
                  <?php
                  for ($i = 1; $i <= 12; $i++) {
                      echo '<div class="col-md-4">
                              <div class="gallery-item">
                                <img src="assets/img/O'.$i.'.webp" alt=" '.$i.'" class="img-fluid rounded">
                              </div>
                            </div>';
                  }
                  ?>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<?php
// Include the footer
include 'footer.php';
?>
