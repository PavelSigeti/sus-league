<template>
    <AppHeader>
        <div class="header-container">
            <button @click="goBack" class="back-button"><img src="@/static/chevron-left.svg" alt="back-button"></button>
            <div>
                Профиль
            </div>
        </div>
    </AppHeader>
    <main>
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-12 profile-grid">
                    <div class="profile-info">
                        <ProfileInfo />
                    </div>
                    <div class="profile-statistics">
                        <ProfileStatistics />
                    </div>
                    <div class="profile-rating">
                        <ProfileRating />
                    </div>
                    <div class="profile-stage-list">
                        <ProfileStageList />
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup>
import AppHeader from "@/components/ui/AppHeader.vue";
import ProfileInfo from "@/components/profile/ProfileInfo.vue"
import ProfileStatistics from "@/components/profile/ProfileStatistics.vue";
import ProfileRating from "@/components/profile/ProfileRating.vue";
import ProfileStageList from "@/components/profile/ProfileStageList.vue";
import { useRouter } from 'vue-router';

const router = useRouter();
const goBack = () => {
    router.back();
};

</script>

<style scoped>
.dashboard-item {
    height: 100%;
    box-sizing: border-box;
    min-height: 271px;
}

.back-button {
    display: flex;
    background: none;
    border: none;
    cursor: pointer;
    justify-content: center;
    padding: 0px;
}

.profile-grid {
    display: grid;
    gap: 30px;
    padding-bottom: 30px;
    grid-template-columns: 1fr 1fr 2fr;
    grid-template-areas: 
        "info stats rating"
        "stages stages stages";
    
}

.profile-grid .dashboard-item {
    margin: 0px;
}

.profile-info {
    grid-area: info;
}

.profile-statistics {
    grid-area: stats;
}

.profile-rating {
    grid-area: rating;
}

.profile-stage-list {
    grid-area: stages;
}

.header-container {
    display: flex;
    gap: 10px;
}

.loading-state {
    color: #555;
}

@media (max-width: 1650px) {
    .profile-grid {
        grid-template-columns: 1fr 1fr; 
        grid-template-areas: 
            "info stats"
            "rating rating"
            "stages stages";
    }
}

@media (max-width: 1045px) {
    .profile-grid {
        grid-template-columns: 1fr;
        grid-template-areas: 
            "info"
            "stats"
            "rating"
            "stages";
    }
}

@media (max-width: 767px) {
    .profile-grid {
        gap: 12px;
        padding-bottom: 12px;
    }
}
</style>