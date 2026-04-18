# 🎓 Group 6 Presentation Guide - Professor Q&A

## Project Overview
**Events Management API with HTTP Basic Authentication**

---

## 📋 TEAM ROLES & WHO ANSWERS WHAT

### **mp-catedrilla** (Project Lead)
**Questions to Answer:**
- Overall project architecture
- Git workflow and repository setup
- How team collaborated
- Deployment process
- **Say:** "I coordinated the team and set up our Git repository with feature branches for each member. We used GitHub for collaboration and I handled the Laravel project initialization."

### **Jwelynie123** (Database Architect)  
**Questions to Answer:**
- Database design and schema
- Migrations (what are they and why)
- PostgreSQL configuration
- Relationships between tables
- **Say:** "I designed the database schema with three main tables: users, events, and participants. I created Laravel migrations which are like version control for the database - they let us define the schema in code and roll back if needed."

**Key Points:**
- Migrations: `database/migrations/`
- Tables: users (11 records), events (100 records), participants (1,000 records)
- Relationships: Event hasMany Participants, Participant belongsTo Event

### **tzahhhahaha** (Auth Developer)
**Questions to Answer:**
- How HTTP Basic Authentication works
- How credentials are validated
- AuthController logic
- Security considerations
- **Say:** "I implemented HTTP Basic Authentication. When a request comes in, we check the Authorization header, decode the base64 credentials, and validate against the database. If valid, user gets access; if not, we return 401 Unauthorized."

**Key Code to Explain:**
```php
// 1. Get header
$authHeader = $request->header('Authorization');

// 2. Extract base64 (remove "Basic ")
$base64Credentials = substr($authHeader, 6);

// 3. Decode to get email:password
$credentials = base64_decode($base64Credentials);
list($email, $password) = explode(':', $credentials, 2);

// 4. Validate against database
$user = User::where('email', $email)->first();
if (!$user || !Hash::check($password, $user->password)) {
    return 401 Unauthorized;
}
```

### **yumaki00** (Events Developer)
**Questions to Answer:**
- EventController CRUD operations
- Eloquent ORM usage
- Validation rules
- How events relate to participants
- **Say:** "I built the EventController with full CRUD. I used Laravel's Eloquent ORM which lets us interact with the database using PHP objects instead of writing SQL. For example, `Event::all()` fetches all events."

**Key Points:**
- Eloquent Model: `app/Models/Event.php`
- Controller: `app/Http/Controllers/EventController.php`
- Validation: `validate(['title' => 'required|string|max:255'])`
- Relationship: `Event::with('participants')`

### **markmasongsong** (Participants Developer)
**Questions to Answer:**
- ParticipantController functionality
- Email uniqueness validation
- How participants register for events
- Event-specific participant queries
- **Say:** "I created the ParticipantController. A key feature is email validation - we check that each participant has a unique email. I also implemented the endpoint to get participants by event ID."

**Key Code:**
```php
// Email uniqueness validation
$validated = $request->validate([
    'email' => 'required|email|unique:participants,email',
    'event_id' => 'required|exists:events,id'
]);

// Get participants for specific event
$participants = Participant::where('event_id', $event_id)->get();
```

### **rayvenedburato** (API Engineer / Documentation)
**Questions to Answer:**
- API routing structure
- How routes connect to controllers
- Database seeders (1,111 records)
- README and documentation
- **Say:** "I handled the API routing in routes/api.php. I also created the database seeder that generates 1,111 records - 11 users, 100 events, and 1,000 participants to exceed the 1,000 record requirement."

**Key Points:**
- Routes: `routes/api.php`
- Seeder: `database/seeders/DatabaseSeeder.php`
- Documentation: `README.md`
- Uses Faker library to generate realistic data

---

## 🔥 COMMON PROFESSOR QUESTIONS & ANSWERS

### Q1: "Why did you choose HTTP Basic Authentication?"
**Answer (tzahhhahaha):**
> "We chose HTTP Basic Authentication because it's a standard HTTP protocol feature, simple to implement, and demonstrates understanding of RFC 7617. It's stateless - no session or token storage needed. While it has limitations (credentials sent every request), it's perfect for demonstrating authentication concepts in an academic project."

**Alternative if asked about security:**
> "Basic Auth requires HTTPS in production since credentials are only base64-encoded (not encrypted). For this academic project, we focused on demonstrating the authentication flow."

### Q2: "How does your authentication work step-by-step?"
**Answer (tzahhhahaha):**
> "1. Client sends request with header: `Authorization: Basic base64(email:password)`
> 2. Server extracts header and decodes base64 to get `email:password`
> 3. Server queries database for user by email
> 4. Server uses `Hash::check()` to verify password against bcrypt hash
> 5. If valid, request proceeds; if invalid, return 401 with `WWW-Authenticate` header"

### Q3: "What Laravel features did you use?"
**Answer (anyone):**
> "We used several Laravel features:
> - **Eloquent ORM** - For database operations using models
> - **Migrations** - For version-controlled database schema
> - **Routing** - Defined RESTful API routes in routes/api.php
> - **Controllers** - Organized logic into controller classes
> - **Validation** - Laravel's built-in request validation
> - **Hash** - For bcrypt password hashing
> - **Faker** - For generating seed data"

### Q4: "Explain your database relationships."
**Answer (Jwelynie123):**
> "We have three tables with these relationships:
> - **Users** - Standalone, for authentication
> - **Events** - Standalone, but hasMany Participants
> - **Participants** - BelongsTo Event (foreign key: event_id)
> 
> This is a one-to-many relationship: one event can have many participants, but each participant belongs to one event. We defined this in the Eloquent models using `hasMany()` and `belongsTo()` methods."

### Q5: "How did you seed 1,000 records?"
**Answer (rayvenedburato):**
> "We used Laravel's database seeders with the Faker library. In DatabaseSeeder.php, we:
> 1. Create 11 users
> 2. Create 100 events, looping with Faker to generate realistic titles and dates
> 3. Create 1,000 participants, assigning each to a random event
> 
> We run it with `php artisan db:seed`. Faker generates realistic fake data like 'Tech Conference', 'John Doe', etc."

### Q6: "What is Eloquent and why use it?"
**Answer (yumaki00 or markmasongsong):**
> "Eloquent is Laravel's ORM (Object-Relational Mapper). Instead of writing SQL like `SELECT * FROM events`, we use `Event::all()`. It:
> - Maps database tables to PHP classes
> - Handles relationships automatically
> - Prevents SQL injection
> - Makes code cleaner and more maintainable"

### Q7: "How do you handle errors?"
**Answer (anyone):**
> "We use Laravel's validation system. For example, when creating an event:
> ```php
> $validated = $request->validate([
>     'title' => 'required|string|max:255',
>     'event_date' => 'required|date'
> ]);
> ```
> If validation fails, Laravel automatically returns a 422 response with error messages. For authentication errors, we return 401 Unauthorized."

### Q8: "What's the difference between your auth and JWT/OAuth?"
**Answer (tzahhhahaha):**
> "HTTP Basic Auth is the simplest form:
> - **Basic Auth** - Send credentials in every request, server validates each time
> - **JWT** - Get token once, use signed token for subsequent requests, token contains claims
> - **OAuth2** - Third-party authorization flow, tokens with refresh capability
> 
> Basic Auth is stateless but sends credentials repeatedly. JWT is stateless and more secure. OAuth2 is for third-party apps."

### Q9: "Why PostgreSQL and not MySQL?"
**Answer (Jwelynie123):**
> "Both work with Laravel. We chose PostgreSQL because:
> - Better support for complex queries
> - More robust for production use
> - Laravel has excellent PostgreSQL support
> - But honestly, either database would work - Laravel abstracts the differences"

### Q10: "Explain the MVC pattern in your project."
**Answer (mp-catedrilla):**
> "Laravel follows MVC:
> - **Model** - User.php, Event.php, Participant.php (data layer)
> - **View** - We don't have views because it's an API (returns JSON instead of HTML)
> - **Controller** - AuthController, EventController, ParticipantController (logic layer)
> 
> Routes direct requests to controllers, which use models, then return JSON responses."

### Q11: "What challenges did you face?"
**Answer (mp-catedrilla - be honest):**
> "We initially implemented Laravel Sanctum (token-based auth), but realized the requirement was for HTTP Basic Authentication. We had to refactor:
> - Changed AuthController to validate Basic Auth headers
> - Removed Sanctum middleware
> - Updated all controllers to check credentials
> - This taught us to read requirements carefully!"

### Q12: "How did you collaborate as a team?"
**Answer (mp-catedrilla):**
> "We used Git with feature branches:
> 1. Each member had their own branch
> 2. Made commits with proper messages
> 3. Pushed to GitHub
> 4. We merged to main branch
> 5. Coordinated through commit messages and GitHub
> 
> This let us work simultaneously without conflicts."

### Q13: "What's base64 and why use it?"
**Answer (tzahhhahaha):**
> "Base64 is an encoding scheme that converts binary data to ASCII text. We use it in Basic Auth to:
> 1. Combine username and password with a colon: `user:pass`
> 2. Encode to base64 so special characters don't break the HTTP header
> 3. It's NOT encryption - just encoding. Anyone can decode it, which is why HTTPS is essential."

### Q14: "Show me how the protected routes work."
**Answer (rayvenedburato or show code):**
> "In routes/api.php, all routes call controllers. In the controllers, the first thing we do is validate Basic Auth:
> ```php
> public function index(Request $request) {
>     $user = AuthController::validateBasicAuth($request);
>     if (!$user) {
>         return AuthController::unauthorizedResponse();
>     }
>     // ... proceed with logic
> }
> ```
> If credentials are invalid, we immediately return 401."

### Q15: "What are migrations and why use them?"
**Answer (Jwelynie123):**
> "Migrations are like version control for the database. Instead of manually creating tables in pgAdmin, we write PHP code that defines the schema. Benefits:
> 1. **Version control** - Schema changes are tracked in Git
> 2. **Rollback** - Can undo changes with `php artisan migrate:rollback`
> 3. **Team collaboration** - Everyone runs same migrations
> 4. **Environment consistency** - Dev, staging, prod have same schema"

---

## 🎯 KEY STATISTICS TO MEMORIZE

| Metric | Number |
|--------|--------|
| **Total Database Records** | **1,111** |
| Users | 11 |
| Events | 100 |
| Participants | 1,000 |
| API Endpoints | 12 |
| Tables | 3 |
| **Requirement Met?** | ✅ YES (1,000+ records) |

---

## 📝 FILE LOCATIONS TO KNOW

If professor asks "where is X?", know these:

| Component | File Path |
|-----------|-----------|
| Routes | `routes/api.php` |
| Event Controller | `app/Http/Controllers/EventController.php` |
| Participant Controller | `app/Http/Controllers/ParticipantController.php` |
| Auth Controller | `app/Http/Controllers/AuthController.php` |
| Event Model | `app/Models/Event.php` |
| Participant Model | `app/Models/Participant.php` |
| User Model | `app/Models/User.php` |
| Migrations | `database/migrations/` |
| Seeder | `database/seeders/DatabaseSeeder.php` |
| Config | `.env` (database credentials) |

---

## 💡 TIPS FOR SUCCESS

1. **Speak confidently** - You built this! Own it.
2. **Use technical terms** - Mention: Eloquent, ORM, MVC, migrations, base64, HTTP headers, RESTful API
3. **Show the code** - If IDE is open, navigate to files while explaining
4. **Demonstrate** - Use Postman to show live API calls
5. **Be honest** - If you don't know, say "I worked on X part, [teammate] handled that"
6. **Stay calm** - Professor wants to see you understand, not just memorize

---

## ⚡ QUICK 30-SECOND PITCH

**If professor says "summarize your project":**

> "We built a RESTful Events Management API using Laravel with HTTP Basic Authentication. We have 1,111 database records (100 events, 1,000 participants) across 3 PostgreSQL tables. The API uses Laravel's Eloquent ORM for database operations and implements full CRUD for events and participants. Authentication is handled via HTTP Basic Auth where credentials are sent in the Authorization header as base64-encoded strings. We used Git for collaboration with feature branches for each team member."

---

**Good luck with your presentation! 🎉**
