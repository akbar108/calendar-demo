<!-- <?php
// The Loop
if (have_posts()) :
    while (have_posts()) : the_post();

        // For URL field, this should be a direct URL string
        // For File field, this will return an array, so get the 'url' key
        $act_file = get_field('image'); // Replace 'image' with your actual ACF field name for the file
        $act_attr = get_field('act-attr');

        // Display a video using <video> tag if the file field returns a valid array
        if ($act_file && is_array($act_file) && isset($act_file['url'])) {
          
                  echo '<img class="pin" src="' . esc_url($act_file['url']) . '" alt="act" id="' . $act_attr . '"/>';
        }

    endwhile; // End of the loop
else :
    // No posts found
    echo '<p>No videos found.</p>';
endif;
?> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Calendar GIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"href="<?= get_template_directory_uri() ?>/style.css" >
</head>
<style>

</style>
<body>
<div class="">
           
        <div id="carouselExample" class="carousel slide" data-bs-touch="false" data-bs-keyboard="false">
        <div class="carousel-inner">
            <?php
            query_posts(array(
                'post_type' => 'post', // Replace with your post type
                'posts_per_page' => -1, // Get all posts
                'order' => 'ASC', // Descending order
            ));
            $active_class = 'active'; // Set the first item as active
            if (have_posts()) :
                while (have_posts()) : the_post();

                    // Get the video URL or file field
                    $video_file = get_field('month'); // Replace 'month' with your ACF field name
                    $attr = get_field('attr'); // Replace 'month' with your ACF field name
                    // Check if the video file is valid
                    if ($video_file && is_array($video_file) && isset($video_file['url'])) {
                        
                        ?>
                        <div class="carousel-item <?php echo $active_class; ?>" id="<?php echo $attr; ?>">
                            <div class="bg-black d-flex justify-content-center align-items-center" style="height: 100vh;">
                                <div class="calendar text-white" style="width: 40%">
                                    <video autoplay muted playsinline loop class="calendar-gif">
                                        <source src="<?php echo esc_url($video_file['url']); ?>" />
                                    </video>
                                    <div id="calendar-<?php echo $attr; ?>" class="calendar-grid"></div>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Reset the active class for subsequent items
                        $active_class = '';
                    }

                endwhile; // End of the loop
                wp_reset_postdata(); // Reset the global post object
            else :
                // No posts found
                echo '<div class="carousel-item active"><p>No videos found.</p></div>';
            endif;
            ?>
        </div>
        <button class="carousel-control-prev" onclick="slideBtn(false)" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" onclick="slideBtn(true)" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        <div class="position-absolute top-0 bg-white d-none" id="chooseActivity" style="">
            <div class="title">Choose Activity</div>
            <ul class="d-flex ">
                <li>Online Meeting</li>
                <li>Offline Meeting</li>
                <li>Work From Home</li>
                <li>Outing</li>
            </ul>
        </div>
    </div>
</div>
    <script>

    let mm
    let y = 2025;
    let days = 0;

    function handleCarouselBtn(currentSlideIndex, totalSlides) {
    const prevBtn = document.querySelector('.carousel-control-prev');
    const nextBtn = document.querySelector('.carousel-control-next');   
    const carouselElement = document.getElementById('carouselExample');

    prevBtn.disabled = currentSlideIndex === 0 ? true : false;
    nextBtn.disabled = currentSlideIndex === totalSlides - 1 ? true : false;
    prevBtn.style.display = currentSlideIndex === 0 ? 'none' : 'block';
    nextBtn.style.display = currentSlideIndex === totalSlides - 1 ? 'none' : 'block';
}
      
    // Listen for the slide event to detect direction and trigger your function
    document.getElementById('carouselExample').addEventListener('slide.bs.carousel', function (event) {
        const direction = event.from < event.to ? 'next' : 'prev';
        // Call your custom function with the direction and target slide index
        onSlideChange(event.to, direction);
    });

    // Initial setup
        const totalSlides = document.querySelectorAll('.carousel-item').length;
        handleCarouselBtn(0, totalSlides);
    
    function onSlideChange(currentSlideIndex, direction) {
       
        mm = currentSlideIndex - 1
        displayCalendar(mm, y);
         days = getDaysInMonth(mm, y);
        // Update carousel controls
        handleCarouselBtn(currentSlideIndex, totalSlides);
        // Add more actions based on the slide index or direction if needed
    }

const daysInMonth = {
            0: 31,
            1: 28, 
            2: 31,
            3: 30,
            4: 31,
            5: 30,
            6: 31,
            7: 31,
            8: 30,
            9: 31,
            10: 30,
            11: 31
        };

    
    function isLeapYear(year) {
        return (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
    }
    
    function getDaysInMonth(month, year) {
        if (month === 1 && isLeapYear(year)) {
            return 29;
        }
        return daysInMonth[month];
    }
    let monthCounter = -1

    function slideBtn(isNext) {

}

async function fetchHolidays(year) {
    const response = await fetch(`https://api-harilibur.netlify.app/api?year=2024`);
    const holidays = await response.json();
    return holidays;
}

// fetchHolidays(y).then(holidays => {
//     console.log(holidays, 'holidays'); // Outputs the list of holidays for the year 2024
// });
function getDayOfWeek(date) {
    const daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    return daysOfWeek[date.getDay()]; // Get the day of the week
}

function getDaysInPreviousMonth(year, month) {
    return new Date(year, month, 0).getDate(); // Returns the last date of the previous month
}

function processDays(year) {
    const month = mm; // Current month from the slider
    const data = [];
    // Main days of the month
    for (let day = 1; day <= days; day++) {
        const date = new Date(year, month, day);
        const dayOfWeek = getDayOfWeek(date);
        data.push({
            timeStamp: date,
            day: dayOfWeek,
            date: date.getDate(),
            month: date.getMonth() + 1,
            year: date.getFullYear()
        });
    }

    // Fill the start of the month grid
    const firstDayIndex = new Date(year, month, 1).getDay();
    const previousMonthDays = getDaysInPreviousMonth(year, month);
    let daysToAddAtStart = (firstDayIndex === 0 ? 6 : firstDayIndex - 1);
    for (let i = 0; i < daysToAddAtStart; i++) {
        const date = new Date(year, month - 1, previousMonthDays - i);
        const dayOfWeek = getDayOfWeek(date);
        data.unshift({
            timeStamp: date,
            day: dayOfWeek,
            date: date.getDate(),
            month: date.getMonth() + 1,
            year: date.getFullYear()
        });
    }

    // Fill the end of the month grid
    const lastDayIndex = new Date(year, month, days).getDay();
    let daysToAddAtEnd = (lastDayIndex === 6 ? 0 : 6 - lastDayIndex);
    for (let i = 1; i <= daysToAddAtEnd; i++) {
        const date = new Date(year, month + 1, i);
        const dayOfWeek = getDayOfWeek(date);

        // Check for duplicates
        if (!data.some(d => d.date === date.getDate() && d.month === date.getMonth() + 1 && d.year === date.getFullYear())) {
            data.push({
                timeStamp: date,
                day: dayOfWeek,
                date: date.getDate(),
                month: date.getMonth() + 1,
                year: date.getFullYear()
            });
        }
    }

    // Ensure data has exactly 42 entries, filling with next month days as needed
    let start = 1;
    while (data.length < 42) {
        const date = new Date(year, month + 1, start);
        const dayOfWeek = getDayOfWeek(date);

        // Again check for duplicates
        if (!data.some(d => d.date === date.getDate() && d.month === date.getMonth() + 1 && d.year === date.getFullYear())) {
            data.push({
                timeStamp: date,
                day: dayOfWeek,
                date: date.getDate(),
                month: date.getMonth() + 1,
                year: date.getFullYear()
            });
        }
        start++;
    }
    return data;
}

const chosenActivities = {};

function displayCalendar(month, year) {

    const calendarDiv = document.querySelector(`#calendar-${month}`);
    if (!calendarDiv) return
    calendarDiv.setAttribute('month', month)
    const days = processDays(y);
    calendarDiv.innerHTML = '';
    let targettedDate = ''
    const chooseActivity = document.querySelector('#chooseActivity');
    let arrTemp = []
    days.forEach(day => {
        const dayElement = document.createElement('div');
        // dayElement.textContent = day.date; sementara
        const formattedDate = day.timeStamp.toLocaleDateString('en-US', {
            weekday: 'long', 
            year: 'numeric', 
            month: 'long',  
            day: 'numeric'  
        });
        dayElement.setAttribute('date-val', day.timeStamp)
        dayElement.setAttribute('id', `${day.date}-${day.month}`)
        
        Object.keys(chosenActivities).forEach((item) => {
            if (item == dayElement.getAttribute('id')) {
                const act = `<img id="${day.date}-${day.month}" src="${chosenActivities[item]}" class="choosedAct position-absolute"/>`
                dayElement.innerHTML = act
            }
        })

        dayElement.className = 'day'; // Add a class for styling
        calendarDiv.appendChild(dayElement);
        let activeActivity = null;
        const allDayElem =document.querySelectorAll('.day')
        dayElement.addEventListener('click', (e) => {
            e.target.classList.add('position-relative')

            // const dayElement = document.createElement('div');
        const dateKey = `${day.date}-${day.month}`;
        
        // Check if an activity was chosen for this day
        if (chosenActivities[dateKey]) {
            const choosedAct = `<img src="${chosenActivities[dateKey]}" class="choosedAct position-absolute"/>`;
            dayElement.insertAdjacentHTML('beforeend', choosedAct);
        }
           const targetDate = e.target
            const existingActivityElements = document.querySelectorAll('.choose-activity');
            existingActivityElements.forEach(activity => {
                activity.remove();
            });

              // Remove existing `choosedAct` on the same `targetDate`
            const existingChoosedAct = targetDate.querySelector('.choosedAct');
                if (existingChoosedAct) {
                    existingChoosedAct.remove();
                }
            // Create the Choose Activity element
            const chooseActivity = document.createElement('div');
            chooseActivity.className = 'choose-activity';
            chooseActivity.setAttribute('id', `${day.date}-${day.month}`)

            const activities = `
            <div class="act-cont" style="width: 70px">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/act-cont.png" alt="" class="w-100">
                <div class="d-flex align-items-center justify-content-center position-absolute" 
                        style="top: 50%;
                        left: 50%;
                        transform: translate(-50%, -60%);
                        ">
                    <div class="d-flex flex-column justify-content-center gap-1">
                        <img class="activities" src="<?php echo get_template_directory_uri(); ?>/assets/cuti.png" alt="" id="cuti">
                        <img class="activities" src="<?php echo get_template_directory_uri(); ?>/assets/meeting.png" alt="" id="meet">
                    </div>
                    <div class="d-flex justify-content-center gap-1">
                        <img class="activities" src="<?php echo get_template_directory_uri(); ?>/assets/online-meet.png" alt="" id="online-meet">
                    </div>
                    <div class="d-flex flex-column justify-content-center gap-1">
                        <img class="activities" src="<?php echo get_template_directory_uri(); ?>/assets/sakit.png" alt="" id="sakit">
                        <img class="activities" src="<?php echo get_template_directory_uri(); ?>/assets/wfh.png" alt="" id="wfh">
                    </div>
                </div>
            </div>  
            `

            // Position it within the clicked dayElement
            chooseActivity.style.top = '0'; // Position below the dayElement
            chooseActivity.style.left = '100%'; // Align to the left
            const img = document.createElement('img');
            img.src = "<?php echo get_template_directory_uri(); ?>/assets/cuti.png"; // Set the src attribute
            img.className = 'act'
            chooseActivity.insertAdjacentHTML('beforeend', activities);
            e.target.appendChild(chooseActivity);

            const activityElements = chooseActivity.querySelectorAll('.activities');
            activityElements.forEach(activity => {
                activity.addEventListener('click', (e) => {
                    const dateKey = `${day.date}-${day.month}`;
                    const activitySrc = e.target.getAttribute('src');
                    // Save chosen activity to the state
                    chosenActivities[dateKey] = activitySrc;

                    // Add the visual representation to the day element
                    const choosedAct = `<img src="${activitySrc}" class="choosedAct position-absolute" id="${dateKey}"/>`;
              
                    targetDate.innerHTML = choosedAct
                });
            });
        })
    });
}

</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>