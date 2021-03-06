import React, { useState } from "react";
import "./Header.css";
import todo from "../../assets/img/todos.svg";
import settings from "../../assets/img/settings.svg";
import { Link } from "react-router-dom";
import Settings from "./Settings.js";
import InstallPWAButton from "../Utilities/InstallPWAButton";

const Header = () => {
  const [toggleSettings, setToggleSettings] = useState(false);

  const showSettings = () => {
    setToggleSettings(!toggleSettings);
  };

  return (
    <header className="header">
      {localStorage.getItem("token") !== null ? (
        <Link to={"/"}>
          <img className="logo" src={todo} alt="To do ikon" />
        </Link>
      ) : (
        <img className="logo" src={todo} alt="To do ikon" />
      )}
      {localStorage.getItem("users_id") !== null ? (
        <img
          onClick={showSettings}
          id="settingsIcon"
          src={settings}
          alt="Instillinger ikon"
        />
      ) : (
        <InstallPWAButton />
      )}
      {toggleSettings && (
        <Settings
          showSettings={showSettings}
          InstallPWAButton={InstallPWAButton}
        />
      )}
    </header>
  );
};

export default Header;
