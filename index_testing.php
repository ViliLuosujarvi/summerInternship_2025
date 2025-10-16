<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Siiranmäki Cabins</title>
  <style>
    body {
      margin:0;
      font-family:Arial, sans-serif;
      background:url("https://images.unsplash.com/photo-1511497584788-876760111969?q=80&w=1932&auto=format&fit=crop&ixlib=rb-4.1.0") no-repeat center center fixed;
      background-size:cover;
      color:#333;
    }
    header {
      text-align:center; 
      background:rgba(46,83,57,0.9); 
      color:#fff; 
      padding:2rem 1rem;
    }
    main {
      padding:2rem; 
      max-width:1000px; 
      margin:auto; 
      background:rgba(255,255,255,0.95); 
      border-radius:12px;
    }
    .cabins {
      display:flex; 
      flex-wrap:wrap; 
      gap:1.5rem; 
      margin-bottom:2rem; 
      justify-content:center;
    }
    .cabin-card {
      background:#fff; 
      padding:1rem; 
      border-radius:12px; 
      box-shadow:0 4px 8px rgba(0,0,0,0.15);
      flex:1 1 30%; 
      max-width:300px; 
      text-align:center; 
      cursor:pointer; 
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .cabin-card:hover {
      transform:scale(1.03); 
      box-shadow:0 8px 15px rgba(0,0,0,0.25);
    }
    .cabin-card img {
      width:100%; 
      border-radius:8px; 
      margin-bottom:0.5rem; 
      max-height:200px; 
      object-fit:cover;
    }
    .reservation {
      background:#fff; 
      padding:2rem; 
      border-radius:12px; 
      box-shadow:0 4px 6px rgba(0,0,0,0.1); 
      margin-bottom:2rem;
    }
    form {display:flex; flex-direction:column; gap:1rem;}
    label {font-weight:bold;}
    input, select, button {padding:0.6rem; border-radius:6px; border:1px solid #ccc; font-size:1rem;}
    button {background:#2e5339; color:white; cursor:pointer; transition:background 0.3s;}
    button:hover {background:#3c6b4b;}
    #confirmation {margin-top:1.5rem; padding:1rem; background:#e6f4ea; border:1px solid #a4d5b2; border-radius:8px;}
    table {width:100%; border-collapse:collapse; margin-top:1rem;}
    th, td {border:1px solid #ccc; padding:0.6rem; text-align:center;}
    th {background:#2e5339; color:white;}
    footer {text-align:center; padding:1.5rem; background:rgba(46,83,57,0.9); color:white; margin-top:2rem;}

    /* Modal */
    #cabinDetails {
      display:none; 
      position:fixed; 
      top:50%; 
      left:50%; 
      transform:translate(-50%, -50%); 
      z-index:9999; 
      width:90%; 
      max-width:700px; 
      max-height:90vh; 
      overflow-y:auto; 
      background:#fff; 
      padding:2rem; 
      border-radius:12px; 
      box-shadow:0 8px 25px rgba(0,0,0,0.3);
    }
    #cabinDetails h2 {text-align:center; color:#2e5339; margin-bottom:1rem;}
    #cabinDetails img {
      width:100%; 
      max-height:400px; 
      object-fit:cover; 
      border-radius:12px; 
      margin-bottom:1rem;
    }
    #closeDetails {
      position:absolute; 
      top:15px; 
      right:15px; 
      background:#d9534f; 
      border:none; 
      color:white; 
      font-weight:bold; 
      padding:0.5rem 1rem; 
      border-radius:6px; 
      cursor:pointer;
      transition: background 0.3s;
    }
    #closeDetails:hover { background:#c9302c; }
    .gallery-controls {
      display:flex; 
      justify-content:space-between; 
      margin-bottom:1rem;
    }
    .gallery-controls button {
      padding:0.5rem 1rem; 
      border:none; 
      border-radius:6px; 
      background:#2e5339; 
      color:white; 
      cursor:pointer; 
      transition: background 0.3s;
    }
    .gallery-controls button:hover { background:#3c6b4b; }
    .thumbnails {
      display:flex; 
      gap:0.5rem; 
      flex-wrap:wrap; 
      justify-content:center; 
      margin-bottom:1rem;
    }
    .thumbnails img {
      width:80px; 
      height:60px; 
      object-fit:cover; 
      border-radius:6px; 
      cursor:pointer; 
      border:2px solid transparent; 
      transition: border 0.2s;
    }
    .thumbnails img.active {border-color:#2e5339;}
    #cabinDescription, #cabinDetails ul {
      text-align:center; 
      margin-bottom:1rem;
    }
    #cabinDetails ul {
      list-style:none; 
      padding:0; 
      margin:0;
    }
    #cabinDetails li { margin-bottom:0.5rem; font-weight:500; }

    @media(max-width:768px){ .cabin-card {flex:1 1 45%;} }
    @media(max-width:480px){ .cabin-card {flex:1 1 100%;} #cabinDetails {padding:1rem;} }
  </style>
</head>
<body>
  <header>
    <h1>Welcome to Siiranmäki Cabins!</h1>
    <p>Choose your cabin and reserve your stay</p>
  </header>

  <main>
    <!-- Cabin Cards -->
    <section class="cabins">
      <div class="cabin-card" data-cabin="helmi1">
        <h2>Helmi 1</h2>
        <img src="https://media.houseandgarden.co.uk/photos/63a1a9b588e2d802928c6499/2:3/w_2000,h_3000,c_limit/MFOX7961.jpg" alt="Helmi 1 cabin">
        <p>Cozy cabin by Käränkävaara ridge. Sleeps 4.</p>
      </div>
      <div class="cabin-card" data-cabin="helmi2">
        <h2>Helmi 2</h2>
        <img src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/615536116.jpg?k=1105a0cd9fd25cd7ebe53a200226d44a240536de1369da10089033a61471f2b9&o=&hp=1" alt="Helmi 2 cabin">
        <p>Rustic cabin with sauna by the ridge. Sleeps 6.</p>
      </div>
      <div class="cabin-card" data-cabin="helmi3">
        <h2>Helmi 3</h2>
        <img src="https://images.fineartamerica.com/images/artworkimages/mediumlarge/1/cabin-at-the-lake-thomas-nay.jpg" alt="Helmi 3 cabin">
        <p>Modern cabin with fireplace by the ridge. Sleeps 6.</p>
      </div>
    </section>

    <!-- Cabin Details Modal -->
    <section id="cabinDetails">
      <button id="closeDetails">Close ✖</button>
      <h2 id="cabinTitle"></h2>
      <div class="gallery-controls">
        <button id="prevImg">⬅ Prev</button>
        <button id="nextImg">Next ➡</button>
      </div>
      <img id="cabinImage" src="" alt="Cabin image">
      <div class="thumbnails" id="thumbnailContainer"></div>
      <p id="cabinDescription"></p>
      <ul>
        <li><strong>Cleaning fee:</strong> <span id="detailCleaning"></span> €</li>
        <li><strong>Linen fee:</strong> <span id="detailLinen"></span> € / person</li>
      </ul>
    </section>

    <!-- Reservation Form -->
    <section class="reservation">
      <h2>Reserve Your Cabin</h2>
      <form id="reservationForm" action="reserve.php" method="POST">
        <label for="cabin">Select Cabin:</label>
        <select id="cabin" name="cabin" required>
          <option value="">--Choose a cabin--</option>
          <option value="helmi1">Helmi 1</option>
          <option value="helmi2">Helmi 2</option>
          <option value="helmi3">Helmi 3</option>
        </select>
        <label for="checkin">Check-in Date:</label>
        <input type="date" id="checkin" name="checkin" required>
        <label for="checkout">Check-out Date:</label>
        <input type="date" id="checkout" name="checkout" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label><input type="checkbox" id="cleaning" name="cleaning"> Add Cleaning Fee</label>
        <label><input type="checkbox" id="linen" name="linen"> Add Linen Fee</label>
        <label for="people">Number of People:</label>
        <input type="number" id="people" name="people" value="1" min="1">
        <p><strong>Total Price: </strong><span id="totalPrice">0</span> €</p>
        <button type="submit">Reserve</button>
      </form>
      <div id="confirmation"></div>
    </section>

    <!-- Reservations Table -->
    <section class="all-reservations">
      <h2>All Reservations</h2>
      <?php
      $conn = new mysqli("localhost", "root", "12345678Riina", "cabin_customer_reservations");
      if ($conn->connect_error) {
          echo "<p>Database connection error.</p>";
      } else {
          $result = $conn->query("SELECT id, cabin, checkin, checkout, total_price, payment_status, created_at FROM reservations ORDER BY created_at DESC");
          if ($result && $result->num_rows > 0) {
              echo "<table>";
              echo "<tr><th>ID</th><th>Cabin</th><th>Check-in</th><th>Check-out</th><th>Total (€)</th><th>Payment</th><th>Reserved At</th></tr>";
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td>{$row['id']}</td>
                          <td>{$row['cabin']}</td>
                          <td>{$row['checkin']}</td>
                          <td>{$row['checkout']}</td>
                          <td>{$row['total_price']}</td>
                          <td>{$row['payment_status']}</td>
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

  <script>
    const cabins = {
      helmi1: {
        title:"Helmi 1",
        description:"A cozy cabin by Käränkävaara ridge. Sleeps 4.",
        priceTiers:{'2':75,'3-5':70,'6+':50},
        cleaning:100,
        linen:25,
        images:[
          "https://media.houseandgarden.co.uk/photos/63a1a9b588e2d802928c6499/2:3/w_2000,h_3000,c_limit/MFOX7961.jpg",
          "https://hips.hearstapps.com/hmg-prod/images/clx100122welldargenzio-002-2-66d768fc6c262.jpg"
        ]
      },
      helmi2: {
        title:"Helmi 2",
        description:"Rustic cabin with sauna. Sleeps 6.",
        priceTiers:{'2':120,'3-5':110,'6+':70},
        cleaning:100,
        linen:25,
        images:[
          "https://cf.bstatic.com/xdata/images/hotel/max1024x768/615536116.jpg?k=1105a0cd9fd25cd7ebe53a200226d44a240536de1369da10089033a61471f2b9&o=&hp=1",
          "https://cdn.mos.cms.futurecdn.net/9AW7pCmj5LmquUGiXLRhUW.jpg"
        ]
      },
      helmi3: {
        title:"Helmi 3",
        description:"Modern cabin with fireplace. Sleeps 6.",
        priceTiers:{'2':110,'3-5':100,'6+':60},
        cleaning:100,
        linen:25,
        images:[
          "https://images.fineartamerica.com/images/artworkimages/mediumlarge/1/cabin-at-the-lake-thomas-nay.jpg",
          "https://gallery.streamlinevrs.com/units-gallery/00/05/CD/image_167989129.jpeg"
        ]
      }
    };

    const cabinCards=document.querySelectorAll('.cabin-card');
    const cabinDetails=document.getElementById('cabinDetails');
    const cabinTitle=document.getElementById('cabinTitle');
    const cabinImage=document.getElementById('cabinImage');
    const cabinDescription=document.getElementById('cabinDescription');
    const detailCleaning=document.getElementById('detailCleaning');
    const detailLinen=document.getElementById('detailLinen');
    const closeDetails=document.getElementById('closeDetails');
    const prevImg=document.getElementById('prevImg');
    const nextImg=document.getElementById('nextImg');
    const thumbnails=document.getElementById('thumbnailContainer');

    let currentImages=[];
    let currentIndex=0;

    cabinCards.forEach(card=>{
      card.addEventListener('click',()=>{
        const key=card.dataset.cabin;
        const sel=cabins[key];
        cabinTitle.textContent=sel.title;
        cabinDescription.textContent=sel.description;
        detailCleaning.textContent=sel.cleaning;
        detailLinen.textContent=sel.linen;
        currentImages=sel.images;
        currentIndex=0;
        cabinImage.src=currentImages[currentIndex];
        updateThumbnails();
        cabinDetails.style.display="block";
      });
    });

    function updateThumbnails(){
      thumbnails.innerHTML="";
      currentImages.forEach((img,i)=>{
        const thumb=document.createElement('img');
        thumb.src=img;
        if(i===currentIndex) thumb.classList.add('active');
        thumb.addEventListener('click',()=>{
          currentIndex=i;
          cabinImage.src=currentImages[currentIndex];
          updateThumbnails();
        });
        thumbnails.appendChild(thumb);
      });
    }

    prevImg.addEventListener('click',()=>{
      if(!currentImages.length) return;
      currentIndex=(currentIndex-1+currentImages.length)%currentImages.length;
      cabinImage.src=currentImages[currentIndex];
      updateThumbnails();
    });
    nextImg.addEventListener('click',()=>{
      if(!currentImages.length) return;
      currentIndex=(currentIndex+1)%currentImages.length;
      cabinImage.src=currentImages[currentIndex];
      updateThumbnails();
    });
    closeDetails.addEventListener('click',()=>{cabinDetails.style.display="none";});

    // Form price calculation
    const form=document.getElementById('reservationForm');
    const totalPriceEl=document.getElementById('totalPrice');
    const confirmation=document.getElementById('confirmation');

    function getNights(checkin,checkout){
      return (new Date(checkout)-new Date(checkin))/(1000*60*60*24);
    }
    function pickPrice(priceTiers,nights){
      if(nights>=6) return priceTiers['6+'];
      if(nights>=3) return priceTiers['3-5'];
      return priceTiers['2'];
    }
    function calculatePrice(){
      const cabinKey=form.cabin.value;
      const nights=getNights(form.checkin.value,form.checkout.value);
      const people=parseInt(form.people.value)||1;
      if(!cabinKey||isNaN(nights)||nights<2){totalPriceEl.textContent="0"; return;}
      let total=pickPrice(cabins[cabinKey].priceTiers,nights)*nights;
      if(form.cleaning.checked) total+=cabins[cabinKey].cleaning;
      if(form.linen.checked) total+=cabins[cabinKey].linen*people;
      totalPriceEl.textContent=total;
    }
    form.addEventListener('input',calculatePrice);
    form.addEventListener('submit',(e)=>{
      const nights=getNights(form.checkin.value,form.checkout.value);
      if(isNaN(nights)||nights<2){e.preventDefault(); alert('Minimum stay is 2 nights.'); return;}
      confirmation.innerHTML="<p>Sending reservation to server…</p>";
    });
  </script>
</body>
</html>
