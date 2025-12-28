<?php include 'header.php'; ?>

<main id="main" class="mt-5 pt-5">

  <section class="casefiles section">
    <div class="container" data-aos="fade-up">

      <div class="section-title mb-4">
        <h2 class="custom-bold-primary">Innovations and Patents</h2>
        <p>Highlighting key achievements in forensic science.</p>
      </div>

        <div class="video-carousel-wrapper" style="position: relative; max-width: 800px; margin: auto; margin-top: 30px;">
          
          <button class="carousel-btn left" id="prevInnovationBtn">‹</button>
        
          <div class="video-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
            <iframe
              id="innovationVideo"
              style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
              allowfullscreen
              loading="lazy"
              referrerpolicy="strict-origin-when-cross-origin">
            </iframe>
          </div>
        
          <button class="carousel-btn right" id="nextInnovationBtn">›</button>
        
        </div>



    </div>
  </section>

</main>


<style>
.carousel-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: #175cdd;
  color: #fff;
  border: none;
  font-size: 30px;
  width: 46px;
  height: 46px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.25s ease;
  z-index: 10;
}

.carousel-btn:hover {
  transform: translateY(-50%) scale(1.1);
}

.carousel-btn.left {
  left: -23px; /* move slightly outside the video */
}

.carousel-btn.right {
  right: -23px; /* move slightly outside the video */
}

.video-carousel-wrapper {
  text-align: center;
  margin: 40px auto;
}


</style>

<?php include 'footer.php'; ?>
