<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pierre Nzetem - Portal</title>
  <style>
    :root {
      --primary-blue: #0a192f;
      --secondary-blue: #172a45;
      --accent-gold: #d4af37;
      --gold-hover: #b89628;
      --text-white: #ffffff;
      --text-muted: #8892b0;
      --bg-light: #f8f9fa;
      --border-light: #e2e8f0;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    body {
      background-color: var(--primary-blue);
      color: var(--text-white);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Barre supérieure & bascule de rôle */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background-color: var(--secondary-blue);
      border-bottom: 2px solid var(--accent-gold);
    }

    .brand {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--accent-gold);
      letter-spacing: 1px;
    }

    .role-switcher {
      display: flex;
      gap: 0.5rem;
      background: rgba(255, 255, 255, 0.1);
      padding: 4px;
      border-radius: 20px;
    }

    .role-btn {
      border: none;
      padding: 6px 16px;
      border-radius: 16px;
      cursor: pointer;
      font-weight: 600;
      background: transparent;
      color: var(--text-white);
      transition: all 0.3s ease;
    }

    .role-btn.active {
      background: var(--accent-gold);
      color: var(--primary-blue);
    }

    /* Conteneur principal */
    main {
      flex: 1;
      padding: 2rem;
      max-width: 1000px;
      margin: 0 auto;
      width: 100%;
    }

    /* DESIGN CÔTÉ UTILISATEUR */
    .view-user {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .user-card {
      background: #ffffff;
      color: #333333;
      border-radius: 12px;
      padding: 2.5rem;
      width: 100%;
      max-width: 550px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
      border-top: 6px solid var(--accent-gold);
    }

    .avatar-wrapper {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .profile-img {
      width: 130px;
      height: 130px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid var(--accent-gold);
      padding: 3px;
      background: var(--primary-blue);
    }

    .name-title {
      font-size: 1.8rem;
      font-weight: 800;
      color: var(--primary-blue);
      margin-bottom: 0.25rem;
    }

    .instagram-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
      color: white;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 0.9rem;
      text-decoration: none;
      font-weight: 600;
      margin: 1rem 0;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .user-bio {
      color: #666;
      line-height: 1.6;
      margin-bottom: 1.5rem;
    }

    .user-actions {
      display: flex;
      gap: 1rem;
      justify-content: center;
    }

    .btn-gold {
      background-color: var(--accent-gold);
      color: var(--primary-blue);
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.2s;
    }

    .btn-gold:hover {
      background-color: var(--gold-hover);
    }

    /* DESIGN CÔTÉ ADMIN */
    .view-admin {
      display: none;
      width: 100%;
    }

    .admin-header {
      margin-bottom: 2rem;
      border-bottom: 1px solid var(--secondary-blue);
      padding-bottom: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
    }

    .admin-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: var(--secondary-blue);
      padding: 1.5rem;
      border-radius: 8px;
      border-left: 4px solid var(--accent-gold);
    }

    .stat-label {
      color: var(--text-muted);
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .stat-value {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--text-white);
      margin-top: 0.5rem;
    }

    .admin-table-wrapper {
      background: var(--secondary-blue);
      border-radius: 8px;
      padding: 1.5rem;
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    th {
      color: var(--accent-gold);
      font-weight: 600;
    }

    td {
      color: var(--text-white);
    }

    .status-badge {
      background: rgba(212, 175, 55, 0.15);
      color: var(--accent-gold);
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.8rem;
    }
  </style>
</head>
<body>

  <header>
    <div class="brand">PORTAIL // PRO</div>
    <div class="role-switcher">
      <button class="role-btn active" onclick="switchView('user')">Vue Utilisateur</button>
      <button class="role-btn" onclick="switchView('admin')">Vue Admin</button>
    </div>
  </header>

  <main>
    <!-- VUE UTILISATEUR : Style épuré, focus image & identité -->
    <section id="user-view" class="view-user">
      <div class="user-card">
        <div class="avatar-wrapper">
          <img 
            src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&auto=format&fit=crop&q=80" 
            alt="Pierre Nzetem" 
            class="profile-img"
          />
        </div>

        <h1 class="name-title">Pierre Nzetem</h1>

        <a href="https://instagram.com" target="_blank" class="instagram-badge">
          <span>📷 @pierre_nzetem</span>
        </a>

        <p class="user-bio">
          Créateur de contenu & profil officiel. Retrouvez ici tous les accès exclusifs et les actualités directes.
        </p>

        <div class="user-actions">
          <a href="#" class="btn-gold">Contacter</a>
        </div>
      </div>
    </section>

    <!-- VUE ADMIN : Style Dashboard sombre, métriques & gestion -->
    <section id="admin-view" class="view-admin">
      <div class="admin-header">
        <div>
          <h2>Panneau d'Administration</h2>
          <p style="color: var(--text-muted); font-size: 0.9rem;">Gestion du compte : Pierre Nzetem</p>
        </div>
        <span class="status-badge">Connecté en SuperAdmin</span>
      </div>

      <div class="admin-stats">
        <div class="stat-card">
          <div class="stat-label">Abonnés Instagram</div>
          <div class="stat-value">12.4K</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Visites du profil</div>
          <div class="stat-value">3,890</div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Statut Campagne</div>
          <div class="stat-value" style="color: var(--accent-gold);">Actif</div>
        </div>
      </div>

      <div class="admin-table-wrapper">
        <h3 style="margin-bottom: 1rem; color: var(--accent-gold);">Dernières interactions</h3>
        <table>
          <thead>
            <tr>
              <th>Plateforme</th>
              <th>Action</th>
              <th>Date</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Instagram</td>
              <td>Nouveau follower via @pierre_nzetem</td>
              <td>Aujourd'hui, 14:20</td>
              <td><span class="status-badge">Complété</span></td>
            </tr>
            <tr>
              <td>Message direct</td>
              <td>Demande de collaboration</td>
              <td>Hier, 19:45</td>
              <td><span class="status-badge">En attente</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <script>
    function switchView(role) {
      const userView = document.getElementById('user-view');
      const adminView = document.getElementById('admin-view');
      const buttons = document.querySelectorAll('.role-btn');

      if (role === 'user') {
        userView.style.display = 'flex';
        adminView.style.display = 'none';
        buttons[0].classList.add('active');
        buttons[1].classList.remove('active');
      } else {
        userView.style.display = 'none';
        adminView.style.display = 'block';
        buttons[0].classList.remove('active');
        buttons[1].classList.add('active');
      }
    }
  </script>
</body>
</html>