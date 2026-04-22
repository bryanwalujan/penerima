<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
        --unima-blue: #1e3a8a;
        --unima-gold: #d4af37;
        --unima-light-blue: #3b82f6;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to bottom right, #f0f7ff, #e6f2ff);
        background-attachment: fixed;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
    }

    .unima-blue { background-color: var(--unima-blue); }
    .unima-gold { color: var(--unima-gold); }

    .card-shadow {
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
        background: white;
    }
    .card-shadow:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    .tab-active {
        color: var(--unima-blue);
        font-weight: 600;
        background-color: #e6f2ff;
        border-bottom: 3px solid var(--unima-light-blue);
    }

    .search-input {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: 24px center;
        background-size: 24px;
        padding-left: 64px;
        border-radius: 14px;
        transition: all 0.3s ease;
    }
    .search-input:focus { box-shadow: 0 0 0 3px rgba(59,130,246,0.3); }

    .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .floating { animation: float 6s ease-in-out infinite; }
    @keyframes float {
        0%   { transform: translateY(0px); }
        50%  { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }

    .hero-pattern {
        background-image: radial-gradient(circle, rgba(30,58,138,0.08) 2px, transparent 2px);
        background-size: 40px 40px;
    }

    .gradient-bg { background: linear-gradient(135deg, var(--unima-blue) 0%, #0f2c6e 100%); }

    .stat-badge {
        background: linear-gradient(135deg, var(--unima-light-blue) 0%, var(--unima-blue) 100%);
        color: white;
        border-radius: 50px;
        padding: 6px 18px;
        display: inline-flex;
        align-items: center;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .stat-badge.clickable { cursor: pointer; transition: transform 0.3s, box-shadow 0.3s; }
    .stat-badge.clickable:hover {
        transform: scale(1.05);
        box-shadow: 0 0 10px rgba(59,130,246,0.5);
        background: linear-gradient(135deg, #2b6cb0 0%, #1e3a8a 100%);
    }

    .profile-border {
        border: 4px solid white;
        box-shadow: 0 0 0 4px var(--unima-light-blue);
        transition: all 0.3s ease;
    }
    .profile-border:hover {
        transform: scale(1.05);
        box-shadow: 0 0 0 4px var(--unima-light-blue), 0 0 20px rgba(59,130,246,0.5);
    }

    .glow-hover:hover { box-shadow: 0 0 20px rgba(59,130,246,0.5); transform: translateY(-2px); }

    .portfolio-tab {
        display: flex; flex-direction: row; align-items: center; justify-content: center;
        padding: 12px 20px; min-width: 120px; transition: all 0.3s ease;
        border-radius: 8px; cursor: pointer; text-decoration: none; color: #4a5568;
    }
    .portfolio-tab .tab-icon { font-size: 20px; margin-right: 8px; transition: transform 0.3s ease; }
    .portfolio-tab .tab-title { font-size: 14px; font-weight: 500; }
    .portfolio-tab .tab-count {
        background-color: #e2e8f0; color: #4a5568; border-radius: 12px;
        padding: 2px 10px; font-size: 12px; font-weight: 600; margin-left: 8px; transition: all 0.3s ease;
    }
    .portfolio-tab:hover, .portfolio-tab.tab-active { background-color: #e6f2ff; }
    .portfolio-tab:hover .tab-icon { transform: scale(1.1); }
    .portfolio-tab:hover .tab-count, .portfolio-tab.tab-active .tab-count { background-color: var(--unima-light-blue); color: white; }
    .portfolio-tab.tab-active .tab-title { color: var(--unima-blue); font-weight: 600; }

    .portfolio-section { padding: 20px; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

    .portfolio-table { width: 100%; border-collapse: collapse; border-radius: 12px; overflow: hidden; background: white; }
    .portfolio-table thead { background: linear-gradient(135deg, var(--unima-light-blue) 0%, var(--unima-blue) 100%); color: white; }
    .portfolio-table th { padding: 12px 16px; text-align: left; font-weight: 600; }
    .portfolio-table td { padding: 12px 16px; border-bottom: 1px solid #e2e8f0; background-color: white; transition: all 0.3s ease; }
    .portfolio-table tbody tr:hover { background-color: #f0f7ff; }
    .portfolio-table tbody tr:hover td { transform: translateX(3px); }
    .portfolio-table tbody tr:last-child td { border-bottom: none; }

    .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center; transition: all 0.3s ease; }
    .status-badge:hover { transform: scale(1.05); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .status-active  { background-color: #dcfce7; color: #166534; }
    .status-pending { background-color: #fef9c3; color: #854d0e; }

    .footer-container { margin-top: auto; }

    .skema-filter { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
    .skema-filter a { display: flex; flex-direction: row; align-items: center; padding: 8px 16px; min-width: 100px; border-radius: 8px; background: #f0f7ff; color: #4a5568; text-decoration: none; transition: all 0.3s ease; }
    .skema-filter a:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .skema-filter a.tab-active { background: var(--unima-light-blue); color: white; font-weight: 600; }
    .skema-filter .tab-title { font-size: 14px; font-weight: 500; }
    .skema-filter .tab-count { background-color: #e2e8f0; color: #4a5568; border-radius: 12px; padding: 2px 8px; font-size: 12px; font-weight: 600; margin-left: 8px; }
    .skema-filter a:hover .tab-count, .skema-filter a.tab-active .tab-count { background-color: white; color: var(--unima-blue); }

    .container-wide { max-width: 1280px; margin-left: auto; margin-right: auto; padding-left: 1.5rem; padding-right: 1.5rem; width: 100%; }
    .content-wrapper { display: flex; flex-direction: column; min-height: 100vh; }
    .main-content { flex: 1; }

    .dosen-card { border-radius: 16px; overflow: hidden; margin-bottom: 1.5rem; background: white; }
    .dosen-header { padding: 1.5rem; }
    .dosen-content { padding: 1.5rem; }
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    .tab-group { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 1rem; background: white; padding: 0.5rem; border-radius: 8px; }
    .tab-item { flex: 1; min-width: 150px; max-width: 200px; }

    .search-container { width: 100%; max-width: 1280px; margin: 0 auto; padding: 0 1.5rem; position: relative; z-index: 20; }
    .search-card-wide { width: 100%; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 25px rgba(0,0,0,0.08); transition: all 0.3s ease; }
    .search-card-wide:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
    .search-form { padding: 1.5rem; }

    .footer-link { transition: all 0.3s ease; }
    .footer-link:hover { color: #93c5fd; transform: translateX(5px); }
    .social-icon { transition: all 0.3s ease; }
    .social-icon:hover { transform: scale(1.2) translateY(-5px); color: #3b82f6; }

    .toggle-button { display: inline-flex; align-items: center; padding: 8px 16px; background: var(--unima-light-blue); color: white; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s ease; }
    .toggle-button:hover { background: var(--unima-blue); transform: scale(1.05); }
    .toggle-button i { margin-left: 8px; transition: transform 0.3s ease; }
    .toggle-button.active i { transform: rotate(180deg); }

    .tab-content-container { transition: max-height 0.5s ease-in-out, opacity 0.3s ease-in-out; max-height: 0; opacity: 0; overflow: hidden; }
    .tab-content-container.active { max-height: 2000px; opacity: 1; }

    .sort-button { display: inline-flex; align-items: center; padding: 6px 12px; background: var(--unima-light-blue); color: white; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.3s ease; margin-left: 8px; }
    .sort-button:hover { background: var(--unima-blue); transform: scale(1.05); }
    .sort-button.active { background: var(--unima-blue); }
    .sort-button i { margin-left: 6px; }
    .sort-button.asc i { transform: rotate(180deg); }

    .hidden { display: none !important; }

    @media (max-width: 768px) {
        .container-wide { padding-left: 1rem; padding-right: 1rem; }
        .tab-group { flex-direction: column; gap: 4px; }
        .tab-item { min-width: 100%; max-width: 100%; }
        .skema-filter { flex-direction: column; gap: 4px; }
        .skema-filter a { width: 100%; justify-content: center; }
        .portfolio-tab { width: 100%; justify-content: space-between; }
        .dosen-header, .dosen-content { padding: 1rem; }
        .portfolio-table th, .portfolio-table td { padding: 8px 12px; font-size: 14px; }
        .search-container { padding: 0 1rem; }
        .search-form { padding: 1rem; }
    }
    @media (max-width: 640px) {
        .portfolio-tab .tab-icon { font-size: 18px; }
        .portfolio-tab .tab-title { font-size: 13px; }
        .portfolio-tab .tab-count { font-size: 11px; padding: 2px 8px; }
        .skema-filter .tab-title { font-size: 13px; }
        .skema-filter .tab-count { font-size: 11px; padding: 2px 8px; }
    }
</style>