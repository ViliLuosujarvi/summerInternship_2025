<?php
// cabins.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cabin Booking</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .cabin-gallery {
      display: flex;
      gap: 1rem;
      justify-content: center;
      padding: 2rem;
    }
    .cabin-card {
      width: 200px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      overflow: hidden;
      cursor: pointer;
      transition: transform 0.2s;
    }
    .cabin-card:hover {
      transform: scale(1.05);
    }
    .cabin-card img {
      width: 100%;
      height: 150px;
      object-fit: cover;
    }
    #cabinDetails {
      display: none;
      background: white;
      max-width: 600px;
      margin: 2rem auto;
      padding: 1.5rem;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.15);
    }
    #cabinImage {
      width: 100%;
      border-radius: 8px;
      margin-bottom: 10px;
    }
    #thumbnailContainer img {
      width: 60px;
      height: 60px;
      margin: 5px;
      object-fit: cover;
      cursor: pointer;
      border-radius: 6px;
      border: 2px solid transparent;
    }
    #thumbnailContainer img.active {
      border-color: #333;
    }
    .controls {
      display: flex;
      justify-content: space-between;
      margin: 10px 0;
    }
    #closeDetails {
      float: right;
      background: #ff4d4d;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <h1 style="text-align:center;">Cabin Booking</h1>

  <div class="cabin-gallery">
    <div class="cabin-card" data-cabin="helmi1">
      <img src="https://media.houseandgarden.co.uk/photos/63a1a9b588e2d802928c6499/2:3/w_2000,h_3000,c_limit/MFOX7961.jpg" alt="Helmi 1" />
      <h3 style="text-align:center;">Helmi 1</h3>
    </div>
    <div class="cabin-card" data-cabin="helmi2">
      <img src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/615536116.jpg?k=1105a0cd9fd25cd7ebe53a200226d44a240536de1369da10089033a61471f2b9&o=&hp=1" alt="Helmi 2" />
      <h3 style="text-align:center;">Helmi 2</h3>
    </div>
    <div class="cabin-card" data-cabin="helmi3">
      <img src="https://images.fineartamerica.com/images/artworkimages/mediumlarge/1/cabin-at-the-lake-thomas-nay.jpg" alt="Helmi 3" />
      <h3 style="text-align:center;">Helmi 3</h3>
    </div>
  </div>

  <!-- Info box -->
  <div id="cabinDetails">
    <button id="closeDetails">Close</button>
    <h2 id="cabinTitle"></h2>
    <img id="cabinImage" src="" alt="Cabin Image" />
    <div class="controls">
      <button id="prevImg">Previous</button>
      <button id="nextImg">Next</button>
    </div>
    <div id="thumbnailContainer"></div>
    <p id="cabinDescription"></p>
    <p>Cleaning: <span id="detailCleaning"></span> €</p>
    <p>Linen: <span id="detailLinen"></span> €/person</p>
  </div>

  <script>
    /* Client-side JS: gallery, thumbnails, and price calculator + validation */
    const cabins = {
      helmi1: {
        title: "Helmi 1",
        description: "A cozy cabin by Käränkävaara ridge. Perfect for a peaceful retreat, sleeps 4.",
        priceTiers: { '2': 75, '3-5': 70, '6+': 50 },
        cleaning: 100,
        linen: 25,
        images: [
          "https://media.houseandgarden.co.uk/photos/63a1a9b588e2d802928c6499/2:3/w_2000,h_3000,c_limit/MFOX7961.jpg",
          "https://hips.hearstapps.com/hmg-prod/images/clx100122welldargenzio-002-2-66d768fc6c262.jpg",
          "https://stofferhome.com/cdn/shop/collections/Screen_Shot_2023-01-17_at_2.25.55_PM_3024x.png?v=1673983797",
          "https://i.pinimg.com/736x/8b/f2/0d/8bf20dad9de73912e8f79ab827f5da4b.jpg"
        ]
      },
      helmi2: {
        title: "Helmi 2",
        description: "Rustic cabin with sauna, ideal for families or groups. Sleeps 6.",
        priceTiers: { '2': 120, '3-5': 110, '6+': 70 },
        cleaning: 100,
        linen: 25,
        images: [
          "https://cf.bstatic.com/xdata/images/hotel/max1024x768/615536116.jpg?k=1105a0cd9fd25cd7ebe53a200226d44a240536de1369da10089033a61471f2b9&o=&hp=1",
          "https://cdn.mos.cms.futurecdn.net/9AW7pCmj5LmquUGiXLRhUW.jpg",
          "https://blog.canadianloghomes.com/wp-content/uploads/2022/02/log-cabin-living-room.jpg",
          "https://i.pinimg.com/736x/3a/c6/80/3ac6805b5b0a3fc46b68366a793b418a.jpg"
        ]
      },
      helmi3: {
        title: "Helmi 3",
        description: "Modern cabin with fireplace and lake views. Sleeps 6.",
        priceTiers: { '2': 110, '3-5': 100, '6+': 60 },
        cleaning: 100,
        linen: 25,
        images: [
          "https://images.fineartamerica.com/images/artworkimages/mediumlarge/1/cabin-at-the-lake-thomas-nay.jpg",
          "https://gallery.streamlinevrs.com/units-gallery/00/05/CD/image_167989129.jpeg",
          "https://gallery.streamlinevrs.com/units-gallery/00/0C/38/image_165319104.jpeg",
          "https://stayovernow.com/wp-content/uploads/2024/01/image_163623794-e1704379674682.webp"
        ]
      }
    };

    const cabinCards = document.querySelectorAll('.cabin-card');
    const cabinDetails = document.getElementById('cabinDetails');
    const cabinTitle = document.getElementById('cabinTitle');
    const cabinImage = document.getElementById('cabinImage');
    const cabinDescription = document.getElementById('cabinDescription');
    const detailCleaning = document.getElementById('detailCleaning');
    const detailLinen = document.getElementById('detailLinen');
    const closeDetails = document.getElementById('closeDetails');
    const prevImg = document.getElementById('prevImg');
    const nextImg = document.getElementById('nextImg');
    const thumbnails = document.getElementById('thumbnailContainer');

    let currentImages = [];
    let currentIndex = 0;

    cabinCards.forEach(card => {
      card.addEventListener('click', () => {
        const key = card.dataset.cabin;
        const selected = cabins[key];
        cabinTitle.textContent = selected.title;
        cabinDescription.textContent = selected.description;
        detailCleaning.textContent = selected.cleaning;
        detailLinen.textContent = selected.linen;
        currentImages = selected.images;
        currentIndex = 0;
        cabinImage.src = currentImages[currentIndex];
        updateThumbnails();
        cabinDetails.style.display = "block";
        cabinDetails.scrollIntoView({ behavior: "smooth" });
      });
    });

    function updateThumbnails() {
      thumbnails.innerHTML = "";
      currentImages.forEach((img, i) => {
        const thumb = document.createElement('img');
        thumb.src = img;
        if (i === currentIndex) thumb.classList.add('active');
        thumb.addEventListener('click', () => {
          currentIndex = i;
          cabinImage.src = currentImages[currentIndex];
          updateThumbnails();
        });
        thumbnails.appendChild(thumb);
      });
    }

    prevImg.addEventListener('click', () => {
      if (!currentImages.length) return;
      currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
      cabinImage.src = currentImages[currentIndex];
      updateThumbnails();
    });
    nextImg.addEventListener('click', () => {
      if (!currentImages.length) return;
      currentIndex = (currentIndex + 1) % currentImages.length;
      cabinImage.src = currentImages[currentIndex];
      updateThumbnails();
    });

    closeDetails.addEventListener('click', () => {
      cabinDetails.style.display = "none";
    });
  </script>

</body>
</html>
