<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Siiranmäki Cabins</title>
  <style>
    /* General page styles */
    body { margin:0; font-family:Arial,sans-serif; background:url("https://images.unsplash.com/photo-1511497584788-876760111969?q=80&w=1932&auto=format&fit=crop&ixlib=rb-4.1.0") no-repeat center center fixed; background-size:cover; color:#333; }
    header { text-align:center; background:rgba(46,83,57,0.9); color:#fff; padding:2rem 1rem; }
    main { padding:2rem; max-width:1000px; margin:auto; background:rgba(255,255,255,0.95); border-radius:12px; }
    .cabins { display:flex; gap:1.5rem; margin-bottom:2rem; }
    .cabin-card { background:#fff; padding:1rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.1); flex:1; text-align:center; cursor:pointer; transition:transform 0.2s; }
    .cabin-card:hover { transform:scale(1.03); }
    .cabin-card img { width:100%; border-radius:8px; margin-bottom:0.5rem; max-height:200px; object-fit:cover; }
    .reservation { background:#fff; padding:2rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.1); margin-bottom:2rem; }
    form { display:flex; flex-direction:column; gap:1rem; }
    label { font-weight:bold; }
    input, select, button { padding:0.6rem; border-radius:6px; border:1px solid #ccc; font-size:1rem; }
    button { background:#2e5339; color:white; cursor:pointer; transition:background 0.3s; }
    button:hover { background:#3c6b4b; }
    #confirmation { margin-top:1.5rem; padding:1rem; background:#e6f4ea; border:1px solid #a4d5b2; border-radius:8px; }
    table { width:100%; border-collapse:collapse; margin-top:1rem; }
    th, td { border:1px solid #ccc; padding:0.6rem; text-align:center; }
    th { background:#2e5339; color:white; }
    footer { text-align:center; padding:1.5rem; background:rgba(46,83,57,0.9); color:white; margin-top:2rem; }
    #cabinDetails { display:none; margin-top:2rem; padding:2rem; background:#fff; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.1); position:relative; }
    #cabinDetails img { width:100%; max-height:400px; object-fit:cover; border-radius:12px; margin-bottom:1rem; }
    #closeDetails { position:absolute; top:10px; right:10px; background:#d9534f; border:none; color:white; padding:0.5rem 1rem; border-radius:6px; cursor:pointer; }
    #closeDetails:hover { background:#c9302c; }
    .gallery-controls { display:flex; justify-content:space-between; margin-bottom:1rem; }
    .thumbnails { display:flex; gap:0.5rem; margin-bottom:1rem; }
    .thumbnails img { width:80px; height:60px; object-fit:cover; border-radius:6px; cursor:pointer; border:2px solid transparent; }
    .thumbnails img.active { border-color:#2e5339; }
  </style>
</head>
<body>
  <header>
    <h1>Welcome to Siiranmäki Cabins</h1>
    <p>Choose your cabin and reserve your stay</p>
  </header>

  <main>
    <!-- Display available cabins -->
    <section class="cabins">
      <div class="cabin-card" data-id="1" data-cabin="helmi1">
        <h2>Helmi 1</h2>
        <img src="https://media.houseandgarden.co.uk/photos/63a1a9b588e2d802928c6499/2:3/w_2000,h_3000,c_limit/MFOX7961.jpg" alt="Helmi 1 cabin">
        <p>Cozy cabin by Käränkävaara ridge. Sleeps 4.</p>
      </div>
      <div class="cabin-card" data-id="2" data-cabin="helmi2">
        <h2>Helmi 2</h2>
        <img src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/615536116.jpg?k=1105a0cd9fd25cd7ebe53a200226d44a240536de1369da10089033a61471f2b9&o=&hp=1" alt="Helmi 2 cabin">
        <p>Rustic cabin with sauna. Sleeps 6.</p>
      </div>
      <div class="cabin-card" data-id="3" data-cabin="helmi3">
        <h2>Helmi 3</h2>
        <img src="https://images.fineartamerica.com/images/artworkimages/mediumlarge/1/cabin-at-the-lake-thomas-nay.jpg" alt="Helmi 3 cabin">
        <p>Modern cabin with fireplace. Sleeps 6.</p>
      </div>
    </section>

    <!-- Cabin details popup -->
    <section id="cabinDetails">
      <button id="closeDetails">Close</button>
      <h2 id="cabinTitle"></h2>
      <div class="gallery-controls">
        <button id="prevImg">◀ Prev</button>
        <button id="nextImg">Next ▶</button>
      </div>
      <img id="cabinImage" src="" alt="Cabin image">
      <div class="thumbnails" id="thumbnailContainer"></div>
      <p id="cabinDescription"></p>
      <p>Cleaning fee: €<span id="detailCleaning"></span></p>
      <p>Linen (per person): €<span id="detailLinen"></span></p>
    </section>

    <!-- Reservation form -->
    <section class="reservation">
      <h2>Reserve Your Cabin</h2>
      <form id="reservationForm" action="reserve.php" method="POST">
        <!-- Cabin selection -->
        <label for="cabin">Select Cabin:</label>
        <select id="cabin" name="cabin" required>
          <option value="">--Choose a cabin--</option>
          <option value="helmi1">Helmi 1</option>
          <option value="helmi2">Helmi 2</option>
          <option value="helmi3">Helmi 3</option>
        </select>

        <label for="people">Number of people:</label>
        <input type="number" id="people" name="people" min="1" max="10" value="1" required>

        <label><input type="checkbox" id="cleaning" name="cleaning"> Include cleaning (€)</label>
        <label><input type="checkbox" id="linen" name="linen"> Include linen (€)</label>

        <!-- Dates -->
        <label for="checkin">Check-in Date:</label>
        <input type="date" id="checkin" name="checkin" required>

        <label for="checkout">Check-out Date:</label>
        <input type="date" id="checkout" name="checkout" required>

        <p>Total price: €<span id="totalPrice">0</span></p>

        <button type="submit">Reserve</button>
      </form>
      <div id="confirmation"></div>
    </section>

    <!-- Display all reservations -->
    <section class="all-reservations">
      <h2>All Reservations</h2>
      <?php
      $conn = new mysqli("localhost", "root", "", "cabin_booking");
      if ($conn->connect_error) {
          echo "<p>Database connection error: " . $conn->connect_error . "</p>";
      } else {
          $sql = "SELECT r.reservation_id, c.name AS cabin_name, g.email AS guest_email, r.reserved_by, r.start_date, r.end_date, r.created_at
                  FROM reservations r
                  JOIN cabins c ON r.cabin_id = c.cabin_id
                  LEFT JOIN guests g ON r.guest_id = g.guest_id
                  ORDER BY r.created_at DESC";
          $result = $conn->query($sql);

          if ($result && $result->num_rows > 0) {
              echo "<table>";
              echo "<tr><th>ID</th><th>Cabin</th><th>Guest Email</th><th>Reserved By</th><th>Check-in</th><th>Check-out</th><th>Created At</th></tr>";
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td>{$row['reservation_id']}</td>
                          <td>".htmlspecialchars($row['cabin_name'])."</td>
                          <td>".htmlspecialchars($row['guest_email'])."</td>
                          <td>".htmlspecialchars($row['reserved_by'])."</td>
                          <td>{$row['start_date']}</td>
                          <td>{$row['end_date']}</td>
                          <td>{$row['created_at']}</td>
                        </tr>";
              }
              echo "</table>";
          } else {
              echo "<p>No reservations yet.</p>";
          }
          $conn->close();
      }
      ?>
    </section>
  </main>

  <footer>
    <p>© 2025 Siiranmäki Cabins. All rights reserved.</p>
  </footer>

  <!-- ✅ Inserted JavaScript here -->
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

    // gallery elements
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

    // Pricing + form
    const form = document.getElementById('reservationForm');
    const totalPriceEl = document.getElementById('totalPrice');
    const confirmation = document.getElementById('confirmation');

    function getNights(checkinStr, checkoutStr) {
      if (!checkinStr || !checkoutStr) return NaN;
      const checkin = new Date(checkinStr);
      const checkout = new Date(checkoutStr);
      return (checkout - checkin) / (1000 * 60 * 60 * 24);
    }

    function pickPriceForNights(priceTiers, nights) {
      if (nights >= 6) return priceTiers['6+'];
      if (nights >= 3 && nights <= 5) return priceTiers['3-5'];
      return priceTiers['2'];
    }

    function calculatePriceClient() {
      const cabinKey = form.cabin.value;
      const nights = getNights(form.checkin.value, form.checkout.value);
      const people = parseInt(form.people.value) || 1;
      const cleaningChecked = form.cleaning.checked;
      const linenChecked = form.linen.checked;

      if (!cabinKey || isNaN(nights) || nights < 2) {
        totalPriceEl.textContent = "0";
        return;
      }

      const perNight = pickPriceForNights(cabins[cabinKey].priceTiers, nights);
      let total = perNight * nights;
      if (cleaningChecked) total += cabins[cabinKey].cleaning;
      if (linenChecked) total += cabins[cabinKey].linen * people;

      totalPriceEl.textContent = total;
    }

    form.addEventListener('input', calculatePriceClient);
  </script>
</body>
</html>
