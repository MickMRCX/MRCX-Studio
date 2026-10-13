# MRCX Studio — site officiel

Site HTML statique en anglais, ressources CSS/JavaScript/images partagées dans `public/assets/`, formulaire de support existant (`support.html` et `support.php`) conservé. Aucun sitemap, robots.txt ou outil de build/test n'était présent. `app-ads.txt` reste en place pour Download Empire.

## Pages et compatibilité

- `/` : accueil présentant les deux jeux.
- `/download-empire/` : présentation existante, captures et lien Google Play conservés.
- `/download-empire/privacy.html` : politique existante, déclarations et date d'effet du 30 septembre 2026 conservées. Seule l'URL de référence du texte change.
- `/tactical-command-idle/` : présentation et emplacements textuels Google Play/App Store, sans URL ni date de sortie inventées.
- `/tactical-command-idle/privacy.html` : brouillon avant publication, `noindex, follow`, sans date d'effet annoncée.
- `/support.html` : support existant, navigation mise à jour.

`.htaccess` redirige `/privacy.html` vers la politique Download Empire et `/download-empire.html` vers sa page dédiée en HTTP 301. Les paramètres de requête sont conservés. Les anciennes pages restent des copies fonctionnelles avec canoniques vers les nouvelles URL si l'hébergement ignore `.htaccess`. Vérifier que l'hébergement autorise `mod_rewrite` et `AllowOverride FileInfo` lors d'une future mise en ligne. Le déploiement existant (`deploy/deploy.sh`) fait un `git pull` puis `rsync --delete public/` vers le répertoire web; il n'a pas été exécuté.

Les références à l'ancienne politique dans les pages du dépôt ont été mises à jour, sauf son adresse dans le texte de la copie de compatibilité. Les liens externes déjà publiés dans les stores, jeux ou autres sites ne peuvent pas être inventoriés à partir de ce dépôt; ils restent compatibles grâce à l'ancienne URL.

## Audit Tactical Command Idle, 12 octobre 2026

Lecture seule de `/Users/pascal/Projects/Project-Breach`. Aucun fichier Unity modifié. Cet audit décrit le code local, pas un binaire de production ni la configuration distante des tableaux de bord. Mise à jour : le manifest fourni ensuite par le développeur remplace le constat initial pour les dépendances directes. Il conserve Analytics 6.3.0, IAP 5.4.3 et Mobile Notifications 2.4.3 et ne déclare plus LevelPlay ni aucun SDK publicitaire. Les observations de code et de réglages ci-dessous proviennent de l’audit initial; le nouveau manifest ne suffit pas à les revalider.

| Élément | Preuve et constat |
| --- | --- |
| Unity Analytics 6.3.0 / UGS Core | `Packages/manifest.json`, `packages-lock.json`, `Assets/Scripts/Analytics/UnityServicesManager.cs`. Initialisation UGS après consentement accordé. |
| Consentement | `InitialAnalyticsConsentPresenter.cs`, `AnalyticsConsentPopupUI.cs`, `SettingsPopupUI.cs`: choix initial et modification dans Settings; consentement persistant Unity. Tester refus, acceptation, retrait et redémarrage dans les builds réels. |
| Événements personnalisés | `GameplayAnalytics.cs`, `GameplayAnalyticsBinding.cs`: bâtiments, recrutement/promotion, missions, récompenses et catégories d'erreurs de restauration/résolution. Ne pas reprendre l'affirmation Download Empire d'absence d'événements personnalisés. |
| Unity IAP 5.4.3 | `Shop/IAP/ShopIapService.cs`: identifiants produit/transaction, crédit local et validation avant confirmation. `PremiumReceiptValidator.cs` est abstrait; aucune implémentation de production trouvée dans les scripts examinés. |
| Sauvegardes locales | `Saves/LocalSaveStore.cs`, `General/LocalSaveSession*`, `EconomySaveData.cs`: fichiers sous `Application.persistentDataPath/Saves`, progression et registre de transactions. Préférences en PlayerPrefs. |
| Unity Mobile Notifications 2.4.3 | `Notifications/*`: notifications locales de missions/bâtiments et préférences sur l'appareil. |
| Diagnostics | `ProjectSettings/UnityConnectSettings.asset`: Cloud Diagnostics reporting désactivé, engine diagnostics activé. `ProjectSettings.asset`: crash-report API désactivée. Événements techniques personnalisés via Analytics. Confirmer les transmissions moteur en build. |
| Authentication / Cloud Save | Pas de package ni d'intégration correspondante trouvés dans les manifestes et scripts examinés. Cela ne garantit pas l'absence de toute sauvegarde distante ou sauvegarde système dans la version finale. |
| Dépendances publicitaires | LevelPlay absent du manifest actualisé fourni. L’audit initial avait trouvé LevelPlay 9.5.1 ainsi que `Assets/LevelPlay/Editor/IronSourceSDKDependencies.xml` et `ISUnityAdsAdapterDependencies.xml`. Leur suppression et l’absence de dépendances natives publicitaires dans les builds restent à vérifier; le manifest seul ne les établit pas. |
| AdMob / UMP | Aucune intégration correspondante trouvée dans les recherches de scripts et fichiers de dépendances examinés. À revérifier dans les binaires finaux. |

La politique du nouveau jeu n'affirme pas que le projet actuel est exempt de SDK publicitaires. Elle décrit l'objectif de release sans publicité et signale la vérification encore nécessaire; aucune déclaration d'utilisation de services publicitaires, de publicité personnalisée ou d'annonces récompensées n'est transférée.

## Informations à confirmer avant publication

1. Le manifest actualisé ne déclare plus de SDK publicitaire. Vérifier également `packages-lock.json`, les anciens adaptateurs, dépendances Android/iOS et références inutilisées; retirer tout résidu et inspecter les binaires et transmissions réseau.
2. Vérifier les payloads automatiques Analytics (identifiants, IP, données techniques), les schémas Dashboard, les choix de consentement et l'arrêt effectif de collecte après retrait.
3. Confirmer les diagnostics moteur et crash reporting en build, leurs données, destinataires et contrôles.
4. Décider si les achats sont actifs au lancement; finaliser et auditer le validateur de reçus, ses prestataires, transmissions et stockage. Vérifier l'accès éventuel aux données de paiement sans inventer une garantie.
5. Confirmer sauvegardes système/distantes, suppression et restauration des données locales, et conservation des transactions.
6. Confirmer durées de conservation Analytics, support et éventuel backend, lieux de traitement, destinataires et mesures de sécurité.
7. Fixer le public visé et les règles applicables aux enfants; ne pas reprendre automatiquement les déclarations de Download Empire.
8. Définir le traitement des demandes d'accès/suppression et le lien avec les identifiants Analytics. Le retrait du consentement ne supprime pas les données antérieures.
9. Mettre à jour le brouillon avec les résultats vérifiés, fixer sa date d'effet et retirer `noindex` une fois approuvé pour publication.
10. Ajouter les liens stores réels lorsqu'ils sont disponibles, sans modifier les emplacements en liens fictifs.

## Stores (aucune modification effectuée)

- Download Empire : remplacer l'adresse de politique par `https://mrcx-studio.com/download-empire/privacy.html` dans les consoles et liens intégrés lors d'une prochaine mise à jour; l'ancienne adresse reste utilisable.
- Tactical Command Idle : après finalisation et publication de la politique, renseigner `https://mrcx-studio.com/tactical-command-idle/privacy.html` et l'URL de support dans les consoles et dans le jeu.
- Google Play Console : remplir Data safety à partir du build final et des services tiers; vérifier la déclaration de publicité, le public visé et les achats. Ne pas déclarer « aucune donnée collectée » simplement parce que le jeu n'a pas de publicité.
- App Store Connect : compléter App Privacy pour les données du jeu et des SDK, configurer les achats si activés et vérifier les manifests de confidentialité ainsi que toute éventuelle activité de suivi dans l'archive finale.

Références officielles consultées : [Unity Analytics Privacy](https://docs.unity.com/en-us/analytics/privacy-and-consent/privacy-overview), [Google Play Data safety](https://support.google.com/googleplay/android-developer/answer/10787469?hl=en), [App Store Connect App Privacy](https://developer.apple.com/help/app-store-connect/manage-app-information/manage-app-privacy), [Apple Third-party SDK requirements](https://developer.apple.com/support/third-party-SDK-requirements/).

## Fichiers

Créés : `public/download-empire/index.html`, `public/download-empire/privacy.html`, `public/tactical-command-idle/index.html`, `public/tactical-command-idle/privacy.html`.

Contenu transféré : présentation et politique Download Empire vers leur dossier dédié; anciens fichiers conservés pour compatibilité.

Modifiés : `public/index.html`, `public/support.html`, `public/privacy.html`, `public/download-empire.html`, `public/.htaccess`, `public/assets/css/style.css`, `public/assets/js/main.js`, `README.md`.

## Validation réalisée

- 113 références internes (liens, images, scripts, styles, action et ancres): aucune cible manquante.
- Comparaison du contenu principal de la politique Download Empire avec Git HEAD: identique après normalisation de l'URL de référence.
- Contrôle d'absence de déclarations publicitaires copiées dans la politique Tactical Command Idle.
- Huit pages, dont les anciennes URL, répondent HTTP 200 via serveur statique local.
- Apache local: configuration syntaxiquement valide, redirections HTTP 301 des deux anciennes URL, avec et sans query string, destinations exactes et paramètres conservés.
- `git diff --check`: valide.
- Styles responsive existants conservés; titre long adapté aux petits écrans, liens de pied de page repliables, état ARIA du menu synchronisé. Contenu visible sans JavaScript.
- Limite: Chrome headless a planté au démarrage dans l'environnement local; validation visuelle mobile/ordinateur et interactions menu/carrousel restent à réaliser dans un navigateur. Node local ne démarre pas (bibliothèque ICU manquante). Aucun envoi du formulaire de support, déploiement, modification de store ou projet Unity effectué.

## Vérification complémentaire du dépôt actuel

Nouvelle lecture des manifestes, scripts, scène BootScene et ProjectSettings après nettoyage publicitaire : LevelPlay absent du manifeste et du lock; dossiers Assets/LevelPlay et Assets/Plugins absents; aucune référence LevelPlay, ironSource, AdMob ou UMP trouvée dans les fichiers examinés. UGS Core confirmé dans le lock. Le lien des paramètres ouvre la politique Tactical Command Idle.

`BootScene.unity` ne référence aucun validateur de reçus; le code IAP exige ce validateur pour permettre les achats. Aucun appel `RequestDataDeletion` trouvé dans les scripts. Cloud Diagnostics reporting reste désactivé, mais `enableCrashReportAPI` est désormais à 1 et engine diagnostics reste activé : l’ancien constat de crash API désactivée est remplacé par celui-ci. Les transmissions réelles doivent être testées dans les builds.

La page précise désormais le défaut fournisseur de 13 mois documenté par Unity pour les données personnelles Analytics, sans le présenter comme une durée configurée et vérifiée pour le jeu. Public visé, activation des achats, réglages distants de conservation et gestion des demandes restent à confirmer. Aucun accès aux consoles Unity/Apple/Google ni test réseau d’un binaire final réalisé.

## Public visé confirmé par la capture Google Play Console

La capture fournie par le développeur indique les tranches 13–15, 16–17 et 18 ans et plus dans « Cible et contenu ». La politique décrit donc un public visé de 13 ans et plus, excluant les moins de 13 ans du public cible. Les classifications de contenu régionales sont distinctes de ce choix. Cette capture ne prouve ni une vérification d’âge dans le jeu, ni la mise en place de mesures de consentement parental. Les mesures applicables aux mineurs de 13–17 ans et la configuration App Store restent à confirmer. Ce constat remplace le point antérieur « public visé à confirmer ».
